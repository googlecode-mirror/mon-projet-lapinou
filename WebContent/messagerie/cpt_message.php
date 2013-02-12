<?php
include "include/sql.php";

$req_mess="select * from `Message` m natural join `Profil` where id_profil='".$_GET['id_disc']."'";
$liste_mess=requete($req_mess);

header('Content-Type: application/xml');
$data="<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?> \n<boite>";
foreach ($liste_mess as $mess) {
	$data.="<message><titre>".$mess['titre']."</titre><nom>".$mess['infos']."</nom><date>".$mess['date']."</date></message>\n";
	}
	$data.="\n</boite>";
	echo $data;
?>