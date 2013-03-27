<?php
	/** formulaire de login
	 * auteur : dominique
	 * 
	 * variable de session :
	 * $_SESSION['hash'] : mémorise un code aléatoire, jusqu'à ce que l'utilisateur soit bien logé.
	 * 
	 *  * Sécurité :
 * 		le mot de passe ne circule pas sur le web, il n'est pas enregistré dans la base
 * 		seule la signature circule (sha1)
 * 
 * 		un nombre aléatoire est tiré sur le serveur, communiqué au client
 * 		client et serveur calcul la signature de mdp(hashé)+nombreAléatoire
 * 
 * 		si les deux résultats coincident : l'utilisateur est connécté
 * 		de cette façon, ce n'est jamais (ou presque) la même signature qui est attendue
	 */

	$_SESSION['hash'] = mt_rand(1,100000); // le nombre aléatoire
	
	// le nombre aléatoire est communiqué sous forme de variable javascript
	echo '<script type="text/javascript" language="Javascript">var randhash = ' . $_SESSION['hash'] . '</script>';
	
	
	// Interdire les tentatives de ,connexion sans javascript
	
?>
	<!--formulaire inscription proprietaire-->
	<noscript>
		<p>Votre navigateur ne prend pas en charge javascript : vous ne pouvez pas vous connecter !
		</p>
	</noscript>
	<script type="text/javascript" language="Javascript" src="scripts/login.js"></script>
	<script type="text/javascript" language="Javascript" src="scripts/sha1.js"></script>
	<script type="text/javascript" language="Javascript">
document.write('	<p name="loginMessage"><?php if (isset($_SESSION['mesLogin'])) echo htmlentities($_SESSION['mesLogin']); unset($_SESSION['mesLogin']); ?></p> ');
document.write('	<form name="login" method="post" action="contenu/login.php" onsubmit="loginHash();">');
document.write('	<fieldset>');
document.write('		<legend>Connexion</legend>');
document.write('		<table>');
document.write('			<tr><td><label>Nom d\'utilisateur :</label></td><td><input type="text" name="user"/> </td></tr>	');
document.write('			<tr><td><label>Password :</label></td><td><input type="password" name="pass"/></td></tr>');
document.write('		</table>');
document.write('		<input type="submit" name="login" value="Connexion"/>');
document.write('	</fieldset>');
document.write('	</form>');
	</script>
