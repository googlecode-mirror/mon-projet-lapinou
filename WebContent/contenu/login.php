<?php	
	if (isset($_POST) and isset($_POST['soumettre'])) {
		// tester le formulaire soumis
	}
	else {
		// tirer au sort un nombre aléatoire
		
		// enregistrer dans la base
	}
?>




<script type="text/javascript" language="Javascript" src="scripts/login.js"></script>
<!--formulaire inscription proprietaire-->
<form name="inscription" method="post" action="login.php" onsubmit="hash();"> <!-- modif dom 19/02/2013 : sécurisation -->
<fieldset>
	<legend>Fiche d'inscription</legend>
	<p id="problemes"><?php if (isset($_GET['mess'])) echo $_GET['mess']; ?></p>
	<table>
		<tr><td><label>Nom d'utilisateur :</label></td>
 			<td><input type="text" name="user" value=""/></td></tr>
		<tr><td><label>Mot de passe :</label></td>
			<td><input type="password" name="pass" /></td></tr>
		<tr><td colspan="2"class="readonly"></td></tr>
	</table>
	<input type="submit" name="soumettre" value="envoyer" /> 
</fieldset>
</form>		

