<?php
/*****************************
 * suppression d'un lapin de *
 * la base de donnees        *
 *****************************/

session_start();
if( isset($_SESSION['identifiant']) && isset($_POST['id']) ){
	$user = $_SESSION['identifiant'];
}else {
	header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
	exit(0);
}

//onsupprime
require_once("sql.php");
if (!connect() ) {
	header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
	exit(0);
}
$sql = "DELETE FROM lapin_lapin WHERE id_lapin = ".$_POST['id'].";";
mysql_query($sql) ;

echo $sql;
//sortie
header('Location: ../index.php?page=profil');	
exit(0);
?>
