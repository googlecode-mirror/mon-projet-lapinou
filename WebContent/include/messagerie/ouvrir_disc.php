<?php
/*****************************************************
 *	script gérant la création d'une discussion		**
 * 													**
 * 		Florent Arnould	-	19 mars 2013			**
 *****************************************************/
/*Rq : double emploi avec une partie de messagerie.php car il
était initialement prévu que l'ouverture serait prévue depuis
tout profil et la messagerie inclue dans chaque profil. */

//inclure la connexion à la base
include_once "../sql.php";

//fonctions

function echec($message) {
//redirige vers la  page d'erreur
	if (isset($bdd_cnx))
  		mysql_close($bdd_cnx);
  	header("Location: ../../index.php?page=erreur&message=$message");
//die ("<div><dl><dt>Echec</dt><dd>$message</dd></dl></div>");	
}

//traitement

//récupérer les données transmises
$intitule=$_REQUEST["intitule"];
$texte=$_REQUEST["texte"];
$dest=$_REQUEST["id_profil"];	//propriétaire du destinaire
$lapin=$_REQUEST["lapin"];		//lapin destinataire
$mid=$_REQUEST["mid"];			//propriétaire connecté
$auteur=$_REQUEST["auteur"];	//lapin de mid

// contrôle
if ($lapin===null || trim($lapin)=="")
	echec("Destinataire non sélectionné !");
if ($auteur===null || trim($auteur)=="")
	echec("Auteur invalide !");
if ($dest===null || trim($dest)=="")
	echec("Erreur de compte destinataire !");
if ($intitule===null || trim($intitule)=="")
	echec("Titre invalide !");
if ($texte===null || trim($texte)=="")
	echec("Message invalide");
if ($mid===null || trim($mid)=="")
	echec("Non connecté !");
	
//!!! il peut sans doute y avoir injection mysql ici !
//Rq : la création d'une discussion avec le propriétaire directement n'est pas permis
//auteur = lapin du membre ?
$req="select id_profil, id_lapin, nomlap from `${prefixe}proprietaire` natural join `${prefixe}lapin` where id_profil=$dest and id_lapin=$lapin";
$ids=requete_par_ligne($req);
if ($ids===null)
	echec("Lapin et propriétaire non liés ou inconnus !".$req);

//ce lapin existe-il ? 
//Rq : à priori oui si le test précédent est passé ; mais la gestion d'erreur est faite à la fin des tests de cohérence
$req="select id_lapin from `${prefixe}lapin` where id_lapin=$auteur";
$idAok=requete_champ_unique($req);
if ($idAok===null)
	echec("Auteur inconnu !".$req);

//à partir d'ici le formulaire est correctement rempli
//s'assurer que le connexxion est en utf-8 pour les caractères accentués.
$utf=preg_match("/utf/",mysql_client_encoding());
if (!$utf) {
	$intitule=utf8_decode($intitule);
	$texte=utf8_decode($texte);
}
//ajout de la discussion
//Rq : il faudrait pouvoir bloquer les transactions (+ commit) jusqu'à réception du nouvel id ; impossible sous MySQL | free
//Rq : pour avoir accès à last_insert_id, la connexion doit être persistente !

/*les bouts de code suivants sont tous présentés comme permettant les transactions, mais
 * - cela provoque systématiquement une erreur dans php
 * - PMA ne tient pas compte de la transaction, malgré le paramètre persistent, et commit de suite
 * - la ligne de commande pas mieux, que ce soit begin, start transaction ou set autocommit=0 !
 * 
 * il n'est donc pas possible de définir des transactions et des requêtes de source différentes peuvent interférer entre elles !
 *
 * mysql_query("SET AUTOCOMMIT=0");
 * 	$req=" BEGIN";
 *  */

//pour rechercher l'id de l'insertion il faut savoir depuis quand l'insertion s'est faite
	$req="select now()";
	$date=requete_champ_unique($req);
	$req="insert into `${prefixe}Discussion` (sujet,intitule,auteur,dest) value (".$ids[1].",'$intitule',$idAok,".$ids[1].")";
	$res=requete($req);
	if (mysql_errno()>0)
		echec("$res erreur lors de la création de la discussion.");
//puisqu'on ne peut garantir qu'il n'y a pas eu d'autre insertion entre les deux, LAST_INSERT_ID() ne peut être utilisée
//on recherche donc la discussion créée avec ces paramètres depuis quelques secondes;
	$req="select id_disc from `${prefixe}Discussion` where sujet='".$ids[1]."' and intitule='$intitule' and auteur=$idAok and dest=".$ids[1]." and date>='$date'";
	$id_d=requete_champ_unique($req);
//Rq : il ne faut SURTOUT PAS tenter d'affecter un retour en comparant ave une valeur : $var=fonction()==null ne met pas le résultat de fonction dans var mais rien (pas même false ou true apparemment) !
	if ($id_d==null || trim($id_d)=="")
		echec("erreur : discussion introuvable. Sans doute non créée.");
	$req="insert into `${prefixe}Message` value (null,'$intitule','$texte',NOW(), $id_d, $auteur)";
	requete($req);
	if (mysql_errno()==0)
		header("Location: ../../index.php?page=messagerie");
	else
  echec("erreur lors de la création du message d'ouverture.");
  ?>
