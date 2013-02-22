<?php
////////////////////////////////////////////
// module d'inscription d'un proprietaire //
// fichier qui ne doit pas etre en acces  //
// direct dansle site                     //
// Cyril THURIER                          //
////////////////////////////////////////////

//TODO : encryptage du mot de passe (cf Dominique ?)

/********************
 * variables utiles *
 ********************/
 // ajout dom : protection injection sql le 22/02/2013
$user = mysql_real_escape_string($_POST['user']);
$nom = mysql_real_escape_string($_POST['nom']);
$prenom = mysql_real_escape_string($_POST['prenom']);
$password = mysql_real_escape_string($_POST['pass']);
$confirm = mysql_real_escape_string($_POST['confpass']);
$codepostal = intval($_POST['cp']);
$region = mysql_real_escape_string($_POST['region']);
$email = mysql_real_escape_string($_POST['mail']);

/*****************************
 * verifications des donnees *
 * envoyees                  *
 *****************************/
$probleme = ""; // localisation du probleme
$erreur = false;
//test user
if( preg_match("/^[0-9A-Za-z\-_]{3,}$/",$user) != 1 ){
	$erreur = true;
	$probleme .= "nom d'utilisateur invalide<br/>";	
}
//test nom
if( preg_match("/^[0-9A-Za-z\-_]{3,}$/",$nom) != 1 ){
	$erreur = true;
	$probleme .= "nom invalide<br/>";	
}
//test prenom
if( preg_match("/^[0-9A-Za-z\-_]{3,}$/",$prenom) != 1 ){
	$erreur = true;
	$probleme .= "pr&eacute;nom invalide<br/>";	
}
//test mot de passe : [dom] le mot de passe est en fait un code hexa sha1
if( preg_match("/^[0-9A-Za-z]{6,}$/",$password) != 1 ){
	$erreur = true;
	$probleme .= "mot de passe invalide<br/>";	
}
//test confirmation
if( $password != $confirm){
	$erreur = true;
	$probleme .= "mots de passe non concordants<br/>";	
}
//test code postal
if( preg_match("/^([0-9]{5})$/",$codepostal) != 1 ){
	$erreur = true;
	$probleme .= "code postal invalide<br/>";	
}
//pas de test pour la region...
//test e-mail
if( preg_match("/^[0-9A-Za-z\-_]{3,}@[0-9A-Za-z\-_]{3,}\.[A-Za-z]{2,3}$/",$email) != 1 ){
	$erreur = true;
	$probleme .= "email invalide<br/>";	
}

//redirection vers l'inscription si erreur
if( $erreur ){
	$message = "donnees invalides<br/>l'inscription n'a pas ete realisee.<br/>";
	$message .= $probleme;
	
	//renvoi tout sauf mots de passe, mail, code postal (parametres en GET )
	header('Location: ../index.php?page=inscription&mess='.urlencode($message).'&user='.urlencode($user).
		'&nom='.urlencode($nom).'&prenom='.urlencode($prenom));
}else{
	//sinon OK
	//tester si l'identifiant existe dejà
	require("connexion.inc.php");
	connect(); //connexion MySQL
	
	//TODO verifier les doublons -----------------------------------***
	
	//insertion	 
	$sql = "INSERT INTO proprietaire (identifiant, nom, prenom, code_postal, region, mail, passwd) ".
			"VALUES ('".$user."','".$nom."','".$prenom."','".$codepostal."','".$region."','".$email."','".$password."');";
			
	if ( ! mysql_query($sql) ){
		$message = $sql." \nun probleme s'est produit.".mysql_error();
		//renvoi tout sauf mots de passe, mail, code postal (parametres en GET )
		header('Location: ../index.php?page=inscription&mess='.urlencode($message).'&user='.urlencode($user).
			'&nom='.urlencode($nom).'&prenom='.urlencode($prenom));		
	}else{
		//c'est bon
		
		//session id
		session_start();
		//if( ! isset($_SESSION['identifiant']) ){  // modif dom : dans tous les cas réinitialiser l'identifiant
			$_SESSION['identifiant'] =$user;//
			session_regenerate_id(true);
		//}			
		//goto profile page
		header('Location: ../index.php?page=profil&user='.urlencode($user));	
	}
	disconnect();  //deconnexion MySQL
}
?>
