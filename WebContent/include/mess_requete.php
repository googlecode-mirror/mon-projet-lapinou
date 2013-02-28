<?php
require_once "sql.php";

if (connect()) {
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
//!!! à tester
	if (isset($_GET['id_disc'])) {
		echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<boite>\n</boite>";
	} else
		if (isset($_GET['id_mess'])) {
		//!!! à tester
			echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<boite>\n</boite>";
		}
}

?>