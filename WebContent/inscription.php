<!DOCTYPE html>
<html>
	<head>
		<title>LAPI.NET - inscription</title>
		<meta name="author" content="master CCI / Cyril THURIER">
		<meta charset="utf-8">
		<script type="text/javascript" language="Javascript" src="scripts/inscription.js"></script>
		<link rel="stylesheet" type="text/css" href="styles/inscription.css" />
	</head>
	<body>
		<section class="contenu">
<!--formulaire inscription proprietaire-->
<form name="inscription" method="post" action="inscrire.php" onsubmit="hash(verif());"> <!-- modif dom 19/02/2013 : sécurisation -->
<fieldset>
	<legend>Fiche d'inscription</legend>
	<p id="problemes"><?php echo $_GET['mess']; ?></p>
	<table>
		<tr><td><label>Nom d'utilisateur :</label></td>
			<td><input type="text" name="user" title="au moins 4 caract&egrave;res" value="<?php echo $_GET['user']; ?>"/></td></tr>
		<tr><td><label>Mot de passe :</label></td>
			<td><input type="password" name="pass" title="au moins 6 caract&egrave;res alphanumeriques"/></td></tr>
		<tr><td colspan="2"class="readonly">(comprend au moins 6 caract&egrave;res alphanumeriques.)</td></tr>
		<tr><td><label>Confirmer le mot de passe :</label></td>
			<td><input type="password" name="confpass"/></td></tr>
		<tr><td><label>Nom :</label></td>
			<td><input type="text" name="nom" value="<?php echo $_GET['nom']; ?>" title="au moins 4 caract&egrave;res"/></td></tr>
		<tr><td><label>Pr&eacute;nom :</label></td>
			<td><input type="text" name="prenom" value="<?php echo $_GET['prenom']; ?>" title="au moins 4 caract&egrave;res"/></td></tr>
		<tr><td><label>Code postal :</label></td>
			<td><input type="number" name="cp" title="au format 00000" onkeyup="explicitRegion()"/></td></tr>
		<tr class="readonly"><td><label>R&eacute;gion :</label></td>
			<td><input type="text" name="region" readonly></td></tr>
		<tr><td><label>Email :</label></td>
			<td><input type="email" name="mail" placeholder="email@example.com" title="au format ###@###.##"></td></tr>
	</table>
	<input type="submit" name="soumetre" value="envoyer" /> 
</fieldset>
</form>		
		</section>
	</body>
</html>
