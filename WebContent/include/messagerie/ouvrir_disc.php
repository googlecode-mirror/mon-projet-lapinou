<?php
//echo phpinfo();
//die("meerde");
/*
echo "<pre>";
print_r($_REQUEST);
print_r($_POST);
print_r($_GET);
echo "</pre>";
*/
include_once "../sql.php";

function echec($message) {
//!!! à développer
if (isset($bdd_cnx))
  mysql_close($bdd_cnx);
  header("Location: ../../index.php?page=erreur&message=$message");
//die ("<div><dl><dt>Echec</dt><dd>$message</dd></dl></div>");	
}

//récupérer les données transmises
$intitule=$_REQUEST["intitule"];
$texte=$_REQUEST["texte"];
$dest=$_REQUEST["id_profil"];	//propriétaire du destinaire
$lapin=$_REQUEST["lapin"];		//lapin destinataire
$mid=$_REQUEST["mid"];			//propriétaire connecté
$auteur=$_REQUEST["auteur"];	//lapin de mid

//echo "contrôle";
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
	
//echo "liens entre profils";
//!!! il peut sans doute y avoir injection mysql ici !
//!!! prévoir la création d'une discussion avec le propriétaire directement (donc $lapin="") => autre champ qui signale la page d'envoi
$req="select id_profil, id_lapin, nomlap from `${prefixe}proprietaire` natural join `${prefixe}lapin` where id_profil=$dest and id_lapin=$lapin";
$ids=requete_par_ligne($req);
if ($ids===null)
	echec("Lapin et propriétaire non liés ou inconnus !".$req);
/*echo "<pre>";
print_r($ids);
echo "</pre>";
*/
//$req="select id_profil from `${prefixe}Profil` where id_profil=$auteur";
$req="select id_lapin from `${prefixe}lapin` where id_lapin=$auteur";
$idAok=requete_champ_unique($req);
if ($idAok===null)
	echec("Auteur inconnu !".$req);

//à partir d'ici le formulaire est correctement rempli
//echo "transaction ".mysql_client_encoding();
$utf=preg_match("/utf/",mysql_client_encoding());
//echo "connexion : $utf<br>\n";
if (!$utf) {
	$intitule=utf8_decode($intitule);
	$texte=utf8_decode($texte);
}
//ajout de la discussion
//!!! il faudrait pouvoir bloquer les transactions (+ commit) jusqu'à réception du nouvel id
//!!! attention ! pour pouvoir avoir accès à last_insert_id, la connexion doit être persistente !

/*les bouts de code suivants sont tous présentés comme permettant les transactions, mais
 * - cela provoque systématiquement une erreur dans php
 * - PMA ne tient pas compte de la transaction, malgré le paramètre persistent, et commit de suite
 * - la ligne de commande pas mieux, que ce soit begin, start transaction ou set autocommit=0 !
 * 
 * il n'est donc pas possible de définir des transactions et des requêtes de source différentes peuvent interférer entre elles ! */

/*mysql_query("SET AUTOCOMMIT=0");
	$req=" BEGIN";
	requete($req);
echo mysql_error().$req; 
	$req="insert into Discussion (sujet,intitule,auteur,dest) value ('$lapin','$intitule',$idAok,".$ids[0].");insert into Message value (null,'$intitule','$texte',LAST_INSERT_ID(),$auteur);COMMIT"; */

//pour rechercher l'id de l'insertion il faut savoir depuis quand l'insertion s'est faite
	$req="select now()";
	$date=requete_champ_unique($req);
//	echo "$req ".mysql_error();
	$req="insert into `${prefixe}Discussion` (sujet,intitule,auteur,dest) value (".$ids[1].",'$intitule',$idAok,".$ids[1].")";
//	echo "$req ";
	$res=requete($req);
	if (mysql_errno()>0)
		echec("$res erreur : ".mysql_error().$req);
//puisqu'on ne peut garantir qu'il n'y a pas eu d'autre insertion entre les deux, LAST_INSERT_ID() ne peut être utilisée
//on recherche donc la discussion créée avec ces paramètres depuis quelques secondes;
	$req="select id_disc from `${prefixe}Discussion` where sujet='".$ids[1]."' and intitule='$intitule' and auteur=$idAok and dest=".$ids[1]." and date>='$date'";
//	echo "$req ";
	$id_d=requete_champ_unique($req);
//echo "'$id_d'";
	//Rq : il ne faut SURTOUT PAS tenter d'affecter un retour en comparant ave une valeur : $var=fonction()==null ne met pas le résultat de fonction dans var mais rien (pas même false ou true apparemment) !
	if ($id_d==null || trim($id_d)=="")
		echec("erreur : ".mysql_error().$req);
//		echo $id_d;
	$req="insert into `${prefixe}Message` value (null,'$intitule','$texte',NOW(), $id_d, $auteur)";
//	$req=mysql_real_escape_string($req);
//echo "$req ";
	requete($req);
if (mysql_errno()==0)
	header("Location: ../../index.php?page=messagerie");
//  echo "discussion lancée !";
/*
	select id_disc from `lapin_Discussion` where 
sujet=\'3\' and intitule=\'test\' and auteur=4 
and dest=3 and date>=\'4\'

erreur : insert into `lapin_Message` value (null,\'test\',\'ok ?\',NOW(), 16, 4)
ur : You have an error in your SQL syntax; check the manual 
that corresponds to your MySQL server version for the right 
syntax to use near \' 4)\' at line 1
insert into `lapin_Message` value (null,\'v�rif\',\'�a marche ...\',NOW(), , 4)

*/
	else
  echec("erreur : ".mysql_error().$req);
  
 // echo "fin";
?>
