<?php
/***********************************************
 * modification d'un profil lapin              *
 ***********************************************/
session_start();
if( isset($_SESSION['identifiant']) ){
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

/********************
 * variables utiles *
 ********************/

$id = mysql_real_escape_string($_POST['id']);
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

//test age
$date = explode("/",$age);
if( count($date) != 3 ){
	$erreur = true;
	$probleme .= "age invalide<br/>";	
}else{
	if( ! checkdate($date[1], $date[0], $date[2] ) ){ //month, day, year
		$erreur = true;
		$probleme .= "age invalide<br/>";		
	}else{
		//mise en forme
		$age = $date[2]."-".$date[1]."-".$date[0];
	}
}


//test sexe
$sexe_valide =  array('m', 'f');
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

if( $erreur ){
	header('Location: ../index.php?page=update_lapin&mess='.urlencode($probleme));	
	exit(0);
}

//OK modification

$sql = 	"UPDATE lapin_lapin SET agelap = '".$age."', race = '".$race."', sexe	= '".$sexe."', ".
		"couleur = '".$couleur."', description = '".$description."', centreInteret = '".$interets."' WHERE id_lapin = '".$id."'";
mysql_query($sql);

//GESTION DE LA PHOTO			
require_once "upload_photo.inc.php";
if(isset($_FILES['photo'])){
	$fich =  enregistrer_photo($_FILES['photo'], $user );
	if( $fich  ){//succes upload
		$sql = "UPDATE lapin_lapin SET photo = '".$fich."' WHERE id_lapin = '".$id."'";
		mysql_query($sql);
	}
}
	
disconnect();

//fin
header('Location: ../index.php?page=profil');	
exit(0);
?>

