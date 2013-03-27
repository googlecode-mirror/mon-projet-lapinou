<?php
/***********************************************
 * affichage des amis de l'utilisateur         *
 ***********************************************/
//session_start();
if( isset($_SESSION['identifiant']) ){
	$user = $_SESSION['identifiant'];
}else {
	header('Location: ../index.php?page=erreur');
	exit(0);
}

require_once("include/sql.php");
if (!connect() ) {
	header('Location: ../index.php?page=erreur');
	exit(0);
}

$sql = "SELECT * FROM lapin_Ami WHERE idProprio = '".$user."';"; //selection des amis

$resultat = mysql_query($sql);
if ( !$resultat  ){
	header('Location: ../index.php?page=erreur');
	exit(0);
}
echo "<article>\n";
echo "<h2>Mes Amis </h2>\n";
while ($ami = mysql_fetch_array($resultat) ){
	//une ligne par ami
	echo "<p><a href='index.php?page=profil&user=".$ami['idAmi']."'><input type=\"button\" value=\"".$ami['idAmi']."\"/></a> <a href='include/supprimerAmi.php?ami=".$ami['idAmi']."'><input value=\Supprimer\" type=\"button\"/></a></p>";
}
echo "</article>\n";
?>


