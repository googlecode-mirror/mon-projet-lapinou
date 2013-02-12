<?php

include sql.php

function echec($message) {
//!!! à développer
if (isset($bdd))
  mysql_close($bdd);
die "<div><dl><dt>Echec</dt><dd>$message</dd></dl></div>");	
}

print_r($_REQUEST);
//récupérer les données transmise
$lapin=$_request["lapin"];
$intitule=$_request["intitule"];
$texte=$_request["texte"];
$dest=$_request["id_profil"];
$auteur=$_request["sid"];	//!!! il s'agit en fait de l'id_profil transmis par la session

echo "contrôle";
// contrôle
if ($lapin===null || trim($lapin)=="")
	echec("Lapin non sélectionné !");
if ($auteur===null || trim($auteur)=="")
	echec("Auteur invalide !");
if ($dest===null || trim($dest)=="")
	echec("Destinataire non sélectionné !");
if ($intitule===null || trim($intitule)=="")
	echec("Auteur invalide !");
if ($texte===null || trim($texte)=="")
	echec("Destinataire non sélectionné !");

	echo "liens entre profils";
//!!! il peut sans doute y avoir injection mysql ici !
//!!! prévoir la création d'une discussion avec le propriétaire directement (donc $lapin="") => autre champ qui signale la page d'envoi
$req="select id_profil, id_lapin from Profil natural join Lapin where id_profil=$dest and nomL=$lapin";
$ids=requete($req);
if ($ids===null)
	echec("Lapin et propriétaire non liés ou inconnus !");
$req="select id_profil from Profil where id_profil=$auteur";
$idAok=requete_champ_unique($req);
if ($idAok===null)
	echec("Auteur inconnu !");

//à partir d'ici le formulaire est correctement rempli
echo "transaction";
//ajout de la discussion
//!!! il faudrait pouvoir bloquer les transactions (+ commit) jusqu'à réception du nouvel id
//!!! attention ! pour pouvoir avoir accès à last_insert_id, la connexion doit être persistente !
	$req="START TRANSACTION;
	insert into Discussion (sujet,intitule,auteur,dest) value ('$lapin','$intitule',$idAok,".$ids[0].");
	insert into Message value (null,'$intitule','$texte',LAST_INSERT_ID(),$auteur);
	commit; ";
if (requete($req))
  echo "discussion lancée !";
else
  echec("erreur : ".mysql_error());
  
  echo "fin";
?>