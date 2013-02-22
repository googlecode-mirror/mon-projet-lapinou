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
	
	
	
	
?>
	<!--formulaire inscription proprietaire-->
	<script type="text/javascript" language="Javascript" src="scripts/login.js"></script>
	<script type="text/javascript" language="Javascript" src="scripts/sha1.js"></script>
	<p name="loginMessage"><?php if (isset($_SESSION['mesLogin'])) echo $_SESSION['mesLogin']; ?></p>
	<form name="login" method="post" action="index.php" onsubmit="loginHash();"> <!-- modif dom 19/02/2013 : sécurisation -->
	<fieldset>
		<legend>Connexion</legend>
		<table>
			<tr><td><label>Nom d'utilisateur :</label></td><td><input type="text" name="user"/> </td></tr>	
			<tr><td><label>Password :</label></td><td><input type="password" name="pass"/></td></tr>
		</table>
		<input type="submit" name="login" value="Connexion"/>
	</fieldset>
	</form>
