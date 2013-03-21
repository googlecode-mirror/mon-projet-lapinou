<?php
/***********************************************
 * inscription effective d'un ami cote serveur *
 ***********************************************/
session_start();
if( isset($_SESSION['identifiant']) ){
	$user = $_SESSION['identifiant'];
}else {
	header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
	exit(0);
}

require_once("include/sql.php");
if (!connect() ) {
	header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
	exit(0);
}

$sql = "SELECT * FROM lapin_Ami WHERE idProprio = '".$user."';";

$resultat = mysql_query($sql);
if ( !$resultat  ){
	header('Location: ../index.php?page=erreur');
	exit(0);
}
echo "<article>\n";
echo "<h2>Mes Amis </h2>\n";
while ($ami = mysql_fetch_array($resultat) ){
	echo "<p><a href='index.php?page=profil&user=".$ami['idAmi']."'>".$ami['idAmi']."</a> <a href='include/supprimerAmi.php?ami=".$ami['idAmi']."'>Supprimer</a></p>";
}
echo "</article>\n";
?>

