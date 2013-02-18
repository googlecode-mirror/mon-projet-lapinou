<?php
require_once "include/sql.php";

if (connect()) {
	//!!! vérifier que la personne est bien connectée (cookie/session)
	//!!! gérer l'erreur d'absence de paramètres GET
	//!!! gérer la sécurité (ne pas inclure GET directement !)
	$req_mess="select texte from `Message` where id_mess='".$_GET['id_mess']."'";
	$corps=requete_champ_unique($req_mess);

	header('Content-Type: application/xml');
	$data="<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<message>";
	$data.="<corps>$corps</corps>";
	$data.="\n</message>";
	echo $data;
} else
//!!! à tester
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<boite>\n</boite>";
?>