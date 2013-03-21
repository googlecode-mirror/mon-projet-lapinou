<?php
/***********************************************
 * suppression effective d'un ami cote serveur *
 ***********************************************/
session_start();
if( isset($_SESSION['identifiant']) ){
	$user = $_SESSION['identifiant'];
}else {
	header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
	exit(0);
}

require_once("sql.php");
if (!connect() ) {
	header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
	exit(0);
}

$sql = "DELETE FROM lapin_Ami WHERE idProprio = '".$user."' AND idAmi='".$_GET['ami']."';";
mysql_query($sql);


header('Location: ../index.php?page=amis');
exit(0);

?>


