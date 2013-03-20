<?php
/*******************************
 * modifier le profil a partir *
 * dela page profil privee     *
 * - proche de inscrire mais   *
 * moins de champs, et update  *
 * au lieu de INSERT           *
 *******************************/


require_once "sql.php"; // avant les mysql_real_escape_string
connect();

session_start();
//t'as rien a foutre là...
if( !isset($_SESSION['identifiant']) ){
	header('Location: ../index.php?page=erreur');
	exit(0);
}
/********************
 * variables utiles *
 ********************/
 // ajout dom : protection injection sql le 22/02/2013
$user = $_SESSION['identifiant'];
$codepostal = intval($_POST['cp']);
$region = mysql_real_escape_string($_POST['region']);
$email = mysql_real_escape_string($_POST['mail']);

/*****************************
 * verifications des donnees *
 * envoyees                  *
 *****************************/
$probleme = ""; // localisation du probleme
$erreur = false;
//test code postal
if( preg_match("/^([0-9]{5})$/",$codepostal) != 1 ){
	$erreur = true;
	$probleme .= "code postal invalide<br/>";	
}
//pas de test pour la region...
//test e-mail
if( preg_match("/^[0-9A-Za-z\-_\.]{3,}@[0-9A-Za-z\-_\.]{3,}\.[A-Za-z]{2,3}$/",$email) != 1 ){
	$erreur = true;
	$probleme .= "email invalide<br/>";	
}
///test taille fichier
if ($_FILES['trombine'] && $_FILES['trombine']['size'] > 1048576) { // >1Mo
	$erreur = true;
	$probleme .= "fichier trop volumineux<br/>";
}

//redirection vers l'inscription si erreur
if( $erreur ){
	$message = "donnees invalides<br/>la modification n'a pas ete realisee n'a pas ete realisee.<br/>";
	$message .= $probleme;
	disconnect();
	header('Location: ../index.php?page=erreur');
	exit(0);
}else{
	//OK !
	$sql = 	"UPDATE lapin_proprietaire SET code_postal = '".$codepostal."', region = '".$region."', mail	= '".$email."'".
		"WHERE identifiant = '".$user."'";
	mysql_query($sql);
	
	//GESTION DE LA PHOTO			
	require_once "upload_photo.inc.php";
	if(isset($_FILES['trombine'])){
		$fich =  enregistrer_photo($_FILES['trombine'], $user );
		if( $fich  ){//succes upload
			$sql = "UPDATE lapin_proprietaire SET trombine = '".$fich."' WHERE identifiant = '".$user."';";
			mysql_query($sql);
		}else die('pb');
	}else die('pb');
	
	disconnect();
	//dans tous les cas
	header('Location: ../index.php?page=profil&user='.urlencode($user));	
	exit(0);
}


?>
