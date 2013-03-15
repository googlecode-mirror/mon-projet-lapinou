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

/********************
 * variables utiles *
 ********************/

$nom = mysql_real_escape_string($_POST['nomlapi']);
$age = mysql_real_escape_string($_POST['age']);
$sexe = mysql_real_escape_string($_POST['sex']);
$race = mysql_real_escape_string($_POST['race']);
$couleur = mysql_real_escape_string($_POST['couleur']);
$interets = mysql_real_escape_string($_POST['interets']);
$description = mysql_real_escape_string($_POST['desc']);

/*****************************
 * verifications des donnees *
 * envoyees                  *
 *****************************/
$probleme = ""; // localisation du probleme
$erreur = false;

//test nom
if( preg_match("/[A-Za-z\-\x{00e0}-\x{00fc}]{3,}+/u",$nom) != 1 ){
	$erreur = true;
	$probleme .= "nom invalide<br/>";	
}

//test age
if( preg_match("/^([0-9]{1,2})$/",$age) != 1 ){
	$erreur = true;
	$probleme .= "age invalide<br/>";	
}

//test sexe
$sexe_valide =  array('male', 'femelle');
if( ! in_array( $sexe, $sexe_valide) ){
	$erreur = true;
	$probleme .= "lapin sans sexe<br/>";	
}
//test race
$races_valides =  array('grande', 'moyenne', 'petite','naine','belier','rustique',
	'fourrure','zombie','toons','cretin','mutante','cuite','indetermine');
if( ! in_array( $race, $races_valides) ){
	$erreur = true;
	$probleme .= "lapin sans race<br/>";	
}
//test couleur
$couleurs_valides =  array('unicolore', 'panache', 'mosaique', 'tachete', 'agouti', 'argente');
if( ! in_array( $couleur, $couleurs_valides) ){
	$erreur = true;
	$probleme .= "lapin sans couleur<br/>";	
}
//test fichier
///test taille fichier
if ($_FILES['photo'] && $_FILES['photo']['size'] > 1048576) { // >1Mo
	$erreur = true;
	$probleme .= "fichier trop volumineux<br/>";
}
//Primary key =  nomLap + identifiant proprietaire
$sql = "SELECT * FROM lapin_lapin WHERE identifiant = '".$user."' AND nomlap = '".$nom."';";
$resultat = mysql_query($sql);
if ( !$resultat  ){
	$erreur = true;
	$message = " \nun probleme s'est produit.";
	disconnect();  //deconnexion MySQL
} else if (mysql_num_rows($resultat) > 0) { // l'identifiant existe déjà
	$erreur = true;
	$message = "Un lapin similaire existe deja";
	disconnect();  //deconnexion MySQL
}
if( $erreur ){
	header('Location: ../index.php?page=ajouter_lapin&mess='.urlencode($message));	
	exit(0);
}

//OK insertion
//recuperer l'id_profil du prorietaire -merci Florent...
$sql = "SELECT id_profil FROM lapin_proprietaire WHERE identifiant = '".$user."';";
$resultat = mysql_query($sql);
$idProprietaire = mysql_fetch_array($resultat);

$sql = "INSERT INTO lapin_lapin (nomlap, agelap, race, sexe, couleur, description, centreInteret, identifiant, id_profil) ".
	"VALUES ('".$nom."','".$age."','".$race."','".$sexe."','".$couleur."','".$description."','".$interets."','".$user."', ".$idProprietaire['id_profil'].");";
			
if ( ! mysql_query($sql) ){
	$message = " \nun probleme s'est produit.";
	header('Location: ../index.php?page=ajouter_lapin&mess='.urlencode($message));	
	exit(0);
}

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
