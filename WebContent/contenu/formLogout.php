<?php /** Formulaire de déconnexion
		*/
?>


	<!--<p name="loginMessage">
	<?php /*if (isset($_SESSION['mesLogin'])) echo $_SESSION['mesLogin'];*/ ?>
	</p>-->
	<form name="logout" method="post" action="contenu/login.php"> <!-- modif dom 19/02/2013 : sécurisation -->
	<fieldset>
		<legend><?php echo $_SESSION['identifiant']; ?></legend>
		<input type="submit" name="logout" value="Déconnexion"/> 	
		<br/><br/>
		<label><a href="index.php?page=profil">Mon profil</a></label>
	</form>
