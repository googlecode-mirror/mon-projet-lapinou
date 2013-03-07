<?php
////////////////////////////////////////////
// module d'inscription d'un proprietaire //
// fichier qui ne doit pas etre en acces  //
// direct dansle site                     //
// Cyril THURIER                          //
////////////////////////////////////////////

require_once "sql.php"; // avant les mysql_real_escape_string
connect();
//DONE : encryptage du mot de passe (dominique) : il est réalisé à la source en javascript
// le mot de passe est haché en sha1 : il ne circule jamais sur le web
// sauf si js n'est pas activé...

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
	
	// modif Dominique
	// politique :
	// 	si l'utilisateur est déjà logé : il peut mettre son compte à jour
	// 	sinon, on interdit la création d'un identifiant déjà existant
	// et on interdit la création d'un nouveau compte à un utilisateur déjà connécté
	session_start();
	if (isset($_SESSION['identifiant'])) {
		if ($user != $_SESSION['identifiant']) {
			// on interdit la création d'un nouveau compte pour un utilisateur logé
			$message = "Vous ne pouvez pas créer un nouveau compte.\nSi vous souhaitez modifier votre compte, vérifiez votre identifiant.\nVous ne pouvez pas modifier votre identifiant.";
			//renvoi tout sauf mots de passe, mail, code postal (parametres en GET )
			header('Location: ../index.php?page=inscription&mess='.urlencode($message).'&user='.urlencode($user).
			'&nom='.urlencode($nom).'&prenom='.urlencode($prenom));		
		} else { // il s'agit d'une modification du compte
			$sql = 	"UPDATE lapin_proprietaire " .
					"SET 	".
							"nom			= '".$nom."',".
							"prenom			= '".$prenom."',".
							"code_postal	= '".$codepostal."',".
							"region			= '".$region."',".
							"mail			= '".$email."',".
							"passwd			= '".$password."'".
					"WHERE identifiant = '".$user."'";
			if ( ! mysql_query($sql) ){
				$message = $sql." \nun probleme s'est produit.".mysql_error();
				//renvoi tout sauf mots de passe, mail, code postal (parametres en GET )
				header('Location: ../index.php?page=inscription&mess='.urlencode($message).'&user='.urlencode($user).
				'&nom='.urlencode($nom).'&prenom='.urlencode($prenom));		
				exit(0);
				
			} else {
				$message = "Compte mis à jour";
				//goto profile page
				header('Location: ../index.php?page=profil&user='.urlencode($user));	
				exit(0);
			}
		}			
	}
	
	
	//tester si l'identifiant existe dejà

	// verifier les doublons : dominique le 26/02
	// On est certain ici que c'est une création de nouveau compte qui est demandé

	$sql = "SELECT * FROM lapin_proprietaire WHERE identifiant = '".$user."'";
	$resultat = mysql_query($sql);
	if ( !$resultat  ){
		$message = $sql." \nun probleme s'est produit.";
		disconnect();  //deconnexion MySQL
		//renvoi tout sauf mots de passe, mail, code postal (parametres en GET )
		header('Location: ../index.php?page=inscription&mess='.urlencode($message).'&user='.urlencode($user).
			'&nom='.urlencode($nom).'&prenom='.urlencode($prenom));	
		exit(0);	
	} else {
		if (mysql_num_rows($resultat) > 0) { // l'identifiant existe déjà
			// il peut s'agir d'une mise à jour des informations du compte

			$message = "Un utilisateur possède déjà cet identifiant : choisissez un autre identifiant";
			disconnect();  //deconnexion MySQL
			//renvoi tout sauf mots de passe, mail, code postal (parametres en GET )
			header('Location: ../index.php?page=inscription&mess='.urlencode($message).'&user='.urlencode($user).
			'&nom='.urlencode($nom).'&prenom='.urlencode($prenom));	
			exit(0);
		}
	}
	
	//GESTION DE LA PHOTO
	require_once "upload_photo.inc.php";
	$photo_enregistree = false;
	if(isset($_FILES['trombine'])){
		$photo_enregistree = enregistrer_photo($_FILES['trombine']);
	}
	
	//insertion	 
	$sql = "INSERT INTO lapin_proprietaire (identifiant, nom, prenom, code_postal, region, mail, passwd) ".
			"VALUES ('".$user."','".$nom."','".$prenom."','".$codepostal."','".$region."','".$email."','".$password."');";
			
	if ( ! mysql_query($sql) ){
		$message = $sql." \nun probleme s'est produit.";
		//renvoi tout sauf mots de passe, mail, code postal (parametres en GET )
		header('Location: ../index.php?page=inscription&mess='.urlencode($message).'&user='.urlencode($user).
			'&nom='.urlencode($nom).'&prenom='.urlencode($prenom));		
	}else{
		//c'est bon
		

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
