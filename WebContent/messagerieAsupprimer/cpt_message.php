<?php
require_once "include/sql.php";

if (connect()) {
	//!!! vérifier que la personne est bien connectée (cookie/session)
	//!!! gérer l'erreur d'absence de paramètres GET
	//!!! gérer la sécurité (ne pas inclure GET directement !)
	$req_mess="select * from `${prefixe}Message` natural join `${prefixe}lapin` natural join `${prefixe}Profil` where id_disc='".$_GET['id_disc']."' order by date";
	$liste_mess=requeteObj($req_mess);

	header('Content-Type: application/xml');
	$data="<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<boite>";
	foreach ($liste_mess as $mess) {
		$data.="<message><id>$mess->id_mess</id><titre>$mess->titre</titre><nom>$mess->nomL</nom><date>$mess->date</date><proprio>$mess->infos</proprio></message>\n";
	}
	$data.="\n</boite>";
	echo $data;
} else
//!!! à tester
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<boite>\n</boite>";
?>