<?php
/***********************************************
 * inscription effective d'un ami cote serveur *
 ***********************************************/
//session_start();
if( isset($_SESSION['identifiant']) && isset($_GET['ami']) ){
	$user = $_SESSION['identifiant'];
}else {
	header('Location: ../index.php?page=erreur');
	exit(0);
}

require_once("sql.php");
if (!connect() ) {
	header('Location: ../index.php?page=erreur');
	exit(0);
}
$ami = $_GET['ami'];
//la personne est-elle deja un ami ?
$sql = "SELECT * FROM lapin_Ami WHERE idProprio = '".$user."' AND idAmi = '".$ami."';";

$resultat = mysql_query($sql);
if ( !$resultat  ){
	header('Location: ../index.php?page=erreur');
	exit(0);
} else if (mysql_num_rows($resultat) > 0) { //oui, pas la peine de le rajouter
	header('Location: ../index.php?page=profil&user='.$ami);
	exit(0);
}
//OK insertion
$sql = "INSERT INTO lapin_Ami (idProprio, idAmi) VALUES ('".$user."','".$ami."');";
				
mysql_query($sql);
header('Location: ../index.php?page=profil&user='.$ami);
exit(0);

?>
