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
$user = $_POST['user'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$passwd = $_POST['pass'];
$confirm = $_POST['confpass'];
$codepostal = $_POST['cp'];
$region = $_POST['region'];
$email = $_POST['mail'];

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
//test mot de passe
if( preg_match("/^[0-9A-Za-z]{6,}$/",$passwd) != 1 ){
	$erreur = true;
	$probleme .= "mot de passe invalide<br/>";	
}
//test confirmation
if( $passwd != $confirm){
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
	header('Location: inscription.php?mess='.urlencode($message).'&user='.urlencode($user).
		'&nom='.urlencode($nom).'&prenom='.urlencode($prenom));
}else{
	//sinon OK
	//tester si l'identifiant existe dejà
	require("include/connexion.inc.php");
	connect(); //connexion MySQL
	
	//TODO verifier
	
	//insertion	 
	$sql = "INSERT INTO proprietaire (identifiant, nom, prenom, code_postal, region, mail, passwd) ".
			"VALUES (".$conversation.",'".$expediteur."','".$destinataire."','".$texte."','".$moment."');";
			
	if ( ! mysql_query($sql) ){
		return false;
	}
	
	disconnect();  //deconnexion MySQL
}
?>
