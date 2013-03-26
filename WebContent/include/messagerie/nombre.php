<?php
/*****************************************************
 *	script de comptage des derniers messages reçus	**
 * 													**
 * 		Florent Arnould	-	12 mars 2013			**
 *****************************************************/

//est appelé indépendemment avant de retourner sur le site : session nécessaire
session_start();

//inclure la connexion à la base et la gestion des requêtes SQL
require_once "../sql.php";
require_once "sqlMess.php";

//le compte ne se fait qu'en étant connecté
if (isset($_SESSION["identifiant"])) {
//connecté : afficher les messages en relation avec le membre et la fiche

//identifier le membre connecté
	$mid=$_SESSION['mid'];

//connecté à la base ?
	if (!connect()) {
	//gestion de l'erreur
		header("Location: ../../index.php?page=erreur&message=La messagerie n'est pas accessible actuellement.");
//		echo "<div class='erreur'>\nLa messagerie n'est pas accessible actuellement.</div>\n";
//		exit;
	}

//aucun membre identifié (ou un des ses lapins)
	if (!isset($mid) && !isset($_SESSION['lid'])) {
	//gestion de l'erreur
	//Rq : à priori inutile avec la connexion ; conservé pour éviter un appel direct
		header("Location: ../../index.php?page=erreur&message=Vous devez être identifié pour accéder à la messagerie.");
	//	echo "<div class='erreur'>\nVous devez être identifié pour accéder à la messagerie.</div>\n";
	//	exit;
	}

//rechercher le nombre de nouveaux messages depuis le dernier accès à sa messagerie
	$req_disc=nonLus($mid);
	$nb_disc=requete_champ_unique($req_disc) or 0;
	
//envoyer le résultat
	header('Content-Type: application/xml');
	$data="<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<nouveaux>";
	$data.="<nombre>$nb_disc</nombre>";
	$data.="</nouveaux>";
	echo $data;
		
}
//non connecté : on ne fait rien

?>