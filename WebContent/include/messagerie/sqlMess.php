<?php
//requêtes de la messagerie

// requêtes utilisées par messagerie.php
function DiscProprio($mid) {
	global $prefixe;
	//requête des discussions du membre connecté
	return "select * from `${prefixe}Discussion` 
			d join `${prefixe}lapin` l1 on d.auteur=l1.id_lapin 
			join `${prefixe}lapin` l2 on d.dest=l2.id_lapin 
			join `${prefixe}proprietaire` p1 on p1.id_profil=l1.id_profil 
			join `${prefixe}proprietaire` p2 on p2.id_profil=l2.id_profil 
			where  p1.id_profil='$mid' or p2.id_profil='$mid' ";
}

function DiscLapin($lid) {
	global $prefixe;
	//requête des discussions du lapin du membre connecté
	return "select * from `${prefixe}Discussion` 
			where auteur='$lid' or dest='$lid'";
}

function DiscAutreCommun () {
	global $prefixe;
	return "select * from `${prefixe}Discussion` 
		d join `${prefixe}lapin` l1 on d.auteur=l1.id_lapin 
		join `${prefixe}lapin` l2 on d.dest=l2.id_lapin 
		join `${prefixe}proprietaire` p1 on p1.id_profil=l1.id_profil 
		join `${prefixe}proprietaire` p2 on p2.id_profil=l2.id_profil ";
}

function DiscAutreProprio ($mid,$pid) {
	global $prefixe;
	return DiscAutreCommun()."where (p1.id_profil='$mid' and p2.id_profil='$pid')
			or (p2.id_profil='$mid' and p1.id_profil='$pid')";
}

function DiscAutreLapin ($mid,$fiche) {
	global $prefixe;
	return DiscAutreCommun()."where (p1.id_profil='$mid' and l2.id_lapin ='$fiche')
			or (p2.id_profil='$mid' and l1.id_lapin ='$fiche')";
}

function IdNomLapin($proprio) {
	global $prefixe;
	return "SELECT id_lapin, nomlap FROM lapin_lapin WHERE `id_profil`='$proprio'";
}

function MajConsultation ($mid) {
	global $prefixe;
	return "INSERT INTO `${prefixe}Consultation` VALUES ('$mid', now()) ON DUPLICATE KEY UPDATE `derniere`=now()";
}

//requêtes utilisées par mess_requete.php

function LireMessages($id_disc) {
	global $prefixe;
	//requête pour obtenir la liste des messages de la discussion donnée
	return "select * from `${prefixe}Message` natural join 
		`${prefixe}lapin` natural join `${prefixe}proprietaire` 
		where id_disc='$id_disc' order by date";
}
	
function LireLeMessage($id_mess) {
	global $prefixe;
	//requête pour obtenir le contenu du message donné
	return "select texte from `${prefixe}Message` 
	where id_mess='$id_mess'";
}

function LireLapinDiscussion($mid,$id_disc) {
	global $prefixe;
	//requête pour obtenir l'id du lapin qui écrit, celui du propriétaire courant
	return "SELECT id_lapin FROM `${prefixe}Discussion` d 
			join ${prefixe}lapin l on d.dest=l.id_lapin
			WHERE id_disc='$id_disc' and l.id_profil='$mid' 
			union
			SELECT id_lapin FROM `${prefixe}Discussion` d 
			join ${prefixe}lapin l on d.auteur=l.id_lapin
			WHERE id_disc='$id_disc' and l.id_profil='$mid'";
}

function ecrireMessage($lid,$id_disc,$titre,$corps) {
	global $prefixe;
	//requête pour ajouter un nouveau message à la discussion
	return "insert into `${prefixe}Message` values 
		('', '$titre','$corps',NOW(),'$id_disc','$lid')";
	//ou (null, ...
}

function coherenceProprioLapin($mid,$auteur) {
	global $prefixe;
	//requête pour tester le lien entre un propriétaire et un lapin
	return "select id_profil, id_lapin from `${prefixe}lapin` 
		where id_profil=$mid and id_lapin='$auteur'";
}

function existeLapin($lapin) {
	global $prefixe;
	//requête pour vérifier que le lapin existe bien
	return "select id_lapin from `${prefixe}lapin` 
			where id_lapin=$lapin";
}

function creerDiscussion($sujet,$intitule,$auteur,$dest) {
	global $prefixe;
	//requête pour ajouter une nouvelle discussion
	return "insert into `${prefixe}Discussion` 
			(sujet,intitule,auteur,dest) 
			value ('$sujet','$intitule',$auteur,$dest)";
}

function derniereDiscussion($sujet,$intitule,$auteur,$dest,$date) {
	global $prefixe;
	//requête pour ajouter une nouvelle discussion
	return "select id_disc from `${prefixe}Discussion` 
			where sujet='$sujet' and intitule='$intitule' 
			and auteur=$auteur and dest=$dest and date>='$date'";
}

// requêtes liées à la boite d'avertissement.
function nonLus($mid) {
	global $prefixe;
	//requête pour ajouter une nouvelle discussion
	return "select count(*) from `${prefixe}Discussion` d
		join `${prefixe}Message` m on d.id_disc=m.id_disc 
		join `${prefixe}Consultation` c on (d.auteur=c.id_profil or d.dest=c.id_profil)
		where c.id_profil='$mid' and m.date>c.derniere";	
}

?>