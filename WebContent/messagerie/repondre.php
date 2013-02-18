<?php
require_once "include/sql.php";

/*$lid=$_POST['lid'];
echo "'$lid ".isset($lid)."'";*/
//$lid=5;
//!!! pour test : de la session
$pid=1;

function xmlErreur($message) {
	header('Content-Type: application/xml');
	$data="<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<erreur>";
	$data.="<contexte>ajout messagerie</contexte>\n";
	$data.="<message>$message</message>\n";
	$data.="</erreur>";
	echo $data;
}

if (!connect()) {
	//gestion de l'erreur
	xmlErreur("La messagerie n'est pas accessible actuellement.");
	exit;
}

if (!isset($pid) && !isset($lid)) {
	//gestion de l'erreur
	xmlErreur("Vous devez être identifié pour accéder à la messagerie.");
	exit;
}
//!!! fonction js réutilisable à mettre dans un fichier séparé

//contrôle des données
if (!isset($_GET["titre"]) || !$_GET["titre"] || !isset($_GET["corps"]) || !$_GET["corps"]) {
	xmlErreur("Veuillez remplir tous les champs.");
	exit;
}
if (!isset($_GET["id_mess"]) || !$_GET["id_mess"] || !isset($_GET["id_disc"]) || !$_GET["id_disc"]) {
	xmlErreur("Un souci a eu lieu avec le formulaire d'envoi.");
	exit;
}

$titre=$_GET["titre"];
$corps=$_GET["corps"];
$id_mess=$_GET["id_mess"];
$id_disc=$_GET["id_disc"];

if (!isset($lid)) {
//retrouver l'id du lapin qui écrit, celui du propriétaire courant
	$req_id="SELECT id_lapin FROM `Discussion` d join Lapin l on d.dest=l.id_lapin
			WHERE id_disc='$id_disc' and l.id_profil='$pid' 
			union
			SELECT id_lapin FROM `Discussion` d join Lapin l on d.auteur=l.id_lapin
			WHERE id_disc='$id_disc' and l.id_profil='$pid'";
	$lid=requete_champ_unique($req_id);
	if (!isset($lid)) {
		xmlErreur("Aucun lapin n'est identifié.");
		exit;
	}
}	

$req_ins="insert into `Message` values ('', '$titre','$corps',NOW(),'$id_disc','$lid')";
$res=requete_champ_unique($req_ins);

header('Content-Type: application/xml');
$data="<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<messagerie>";
$data.="<contexte>ajout</contexte>\n";
$data.="<retour>ok</retour>\n";
$data.="</messagerie>";
echo $data;

//echo "insertion : $res ".mysql_error();


?>