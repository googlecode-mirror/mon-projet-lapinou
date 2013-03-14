<?php
/***********************************************
 * inscription effective du lapin cote serveur *
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

//verification du formulaire
/********************
 * variables utiles *
 ********************/

$nom = mysql_real_escape_string($_POST['nomlapi']);
$sexe = mysql_real_escape_string($_POST['sex']);
$race = mysql_real_escape_string($_POST['race']);
$couleur = mysql_real_escape_string($_POST['couleur']);
$interets = mysql_real_escape_string($_POST['interets']);
$description = mysql_real_escape_string($_POST['desc']);

//TODO PK nomLap + identifiant proprietaire


?>
<html>
<body>
<p><?php echo $nom; ?></p>
<p><?php echo $sexe; ?></p>
<p><?php echo $race; ?></p>
<p><?php echo $couleur; ?></p>
<p><?php echo $interets; ?></p>
<p><?php echo $description; ?></p>
</body>
</html>
