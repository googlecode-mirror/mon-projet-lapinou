<?php
include "include/sql.php";

$req_mess="select * from `Message` m natural join `Profil` where auteur='".$_GET['id_disc'];
$liste_mess=requete($req_mess);

$data="";
foreach ($liste_mess as $mess) {
	$data.="<message><titre>".$mess['titre']."</titre><nom>".$mess['infos']."</nom><date>".$mess['date']."</date></message>\n";
	}
	echo $data;
?>