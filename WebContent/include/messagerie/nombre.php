<?php
/*		script de comptage des derniers messages reçus
 * 
 * 		écrit par Florent Arnould	-	3 mars 2013
 */

session_start();
require_once "../sql.php";
require_once "sqlMess.php";


if (isset($_SESSION["identifiant"])) {
//connecté : afficher les messages en relation avec le membre et la fiche
$mid=$_SESSION['mid'];

if (!connect()) {
	//gestion de l'erreur
		echo "<div class='erreur'>\nLa messagerie n'est pas accessible actuellement.</div>\n";
		exit;
	}

	if (!isset($mid) && !isset($_SESSION['lid'])) {
	//gestion de l'erreur
	//à priori inutile avec la connexion ; conservé pour éviter un appel direct
		echo "<div class='erreur'>\nVous devez être identifié pour accéder à la messagerie.</div>\n";
		exit;
	}

	$req_disc=nonLus($mid);
	/*"select count(*) from `${prefixe}Discussion` d
		join `${prefixe}Message` m on d.id_disc=m.id_disc 
		join `${prefixe}Consultation` c on (d.auteur=c.id_profil or d.dest=c.id_profil)
		where c.id_profil='$mid' and m.date>c.derniere";*/
//	echo $req_disc;
	$nb_disc=requete_champ_unique($req_disc) or 0;
	
	header('Content-Type: application/xml');
	$data="<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<nouveaux>";
	$data.="<nombre>$nb_disc</nombre>";
	$data.="</nouveaux>";
	echo $data;
		
}
//non connecté : on ne fait rien

?>