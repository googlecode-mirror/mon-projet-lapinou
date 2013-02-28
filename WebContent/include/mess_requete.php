<?php
session_start();

require_once "sql.php";

function xmlErreur($message) {
	header('Content-Type: application/xml');
	$data="<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<erreur>";
	$data.="<contexte>ajout messagerie</contexte>\n";
	$data.="<message>$message</message>\n";
	$data.="</erreur>";
	echo $data;
}

//!!! à prendre en compte !
$mid=$_SESSION['mid'];
/*
		if (!isset($mid) && !isset($lid)) {
	//gestion de l'erreur
	xmlErreur("Vous devez être identifié pour accéder à la messagerie.");
	exit;
}
*/

if (connect()) {
	if (!isset($_GET['corps'])) {
		if (isset($_GET['id_disc'])) {
		//!!! vérifier que la personne est bien connectée (cookie/session)
	//!!! gérer l'erreur d'absence de paramètres GET
	//!!! gérer la sécurité (ne pas inclure GET directement !)
			$req_mess="select * from `${prefixe}Message` natural join `${prefixe}Lapin` natural join `${prefixe}Profil` where id_disc='".$_GET['id_disc']."' order by date";
			$liste_mess=requeteObj($req_mess);

			header('Content-Type: application/xml');
			$data="<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<boite>";
			foreach ($liste_mess as $mess) {
				$data.="<message><id>$mess->id_mess</id><titre>$mess->titre</titre><nom>$mess->nomL</nom><date>$mess->date</date><proprio>$mess->infos</proprio></message>\n";
			}
			$data.="\n</boite>";
			echo $data;
		} else
			if (isset($_GET['id_mess'])) {
			//!!! vérifier que la personne est bien connectée (cookie/session)
			//!!! gérer l'erreur d'absence de paramètres GET
			//!!! gérer la sécurité (ne pas inclure GET directement !)
				$req_mess="select texte from `${prefixe}Message` where id_mess='".$_GET['id_mess']."'";
				$corps=requete_champ_unique($req_mess);

				header('Content-Type: application/xml');
				$data="<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<message>";
				$data.="<corps>$corps</corps>";
				$data.="\n</message>";
				echo $data;
			}
	} else {
		//un message est envoyé pour ajout

	//contrôle des données
		$ok=true;
		if (!isset($_GET["titre"]) || !$_GET["titre"] || !isset($_GET["corps"]) || !$_GET["corps"]) {
			xmlErreur("Veuillez remplir tous les champs.");
			$ok=false;
		}
		if (!isset($_GET["id_mess"]) || !$_GET["id_mess"] || !isset($_GET["id_disc"]) || !$_GET["id_disc"]) {
			xmlErreur("Un souci a eu lieu avec le formulaire d'envoi.");
			$ok=false;
		}
		if ($ok) {
			$titre=$_GET["titre"];
			$corps=$_GET["corps"];
			$id_mess=$_GET["id_mess"];
			$id_disc=$_GET["id_disc"];

			if (!isset($_SESSION['lid'])) {
			//retrouver l'id du lapin qui écrit, celui du propriétaire courant
				$req_id="SELECT idLap FROM `${prefixe}Discussion` d join ${prefixe}lapin l on d.dest=l.idLap
						WHERE id_disc='$id_disc' and l.id_profil='$mid' 
						union
						SELECT idLap FROM `${prefixe}Discussion` d join ${prefixe}lapin l on d.auteur=l.idLap
						WHERE id_disc='$id_disc' and l.id_profil='$mid'";
				$lid=requete_champ_unique($req_id);
				if (!isset($lid)) {
					xmlErreur("Aucun lapin n'est identifié. $mid '".implode($_SESSION)."' $req_id");
					$ok=false;
				}
			} else
				$lid=$_SESSION['lid'];
			if ($ok) {
	
				$req_ins="insert into `${prefixe}Message` values ('', '$titre','$corps',NOW(),'$id_disc','$lid')";
				$res=requete_champ_unique($req_ins);

				header('Content-Type: application/xml');
				$data="<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<messagerie>";
				$data.="<contexte>ajout</contexte>\n";
				$data.="<retour>ok</retour>\n";
				$data.="</messagerie>";
				echo $data;
			}	
		}	
	}	
} else {
//!!! à tester
	if (!isset($_GET['corps'])) {
		if (isset($_GET['id_disc'])) {
			echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<boite>\n</boite>";
		} else
			if (isset($_GET['id_mess'])) {
			//!!! à tester
				echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<boite>\n</boite>";
			}
	} else {
	//gestion de l'erreur
		xmlErreur("La messagerie n'est pas accessible actuellement.");
	}
}

?>