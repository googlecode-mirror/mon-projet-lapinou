<?php
include "include/sql.php";

$req_profil="select * from `Profil`";
$liste_profil=requete($req_profil);

if ($liste_profil!==null) {
	echo "<pre>";
	print_r($liste_profil);
	echo "</pre>";
} else
echo "<i>Aucun profil trouvé.</i> ".mysql_error();
	

?>