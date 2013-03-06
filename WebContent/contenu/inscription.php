
		<script type="text/javascript" language="Javascript" src="scripts/inscription.js"></script>
		<script type="text/javascript" language="Javascript" src="scripts/sha1.js"></script>


<!--formulaire inscription proprietaire-->
<noscript>Vous ne pouvez pas vous inscrire car javascript n'est pas activé.</noscript>
<script type="text/javascript" language="Javascript">
document.write('<form name="inscription" method="post" action="include/inscrire.php" onsubmit="return hash(verif());"> ');
document.write('<fieldset>');
document.write('	<legend>Fiche d\'inscription</legend>');
document.write('	<p id="problemes"><?php if (isset($_GET['mess'])) echo $_GET['mess']; ?></p>');
document.write('	<table>');
document.write('		<tr><td><label>Nom d\'utilisateur :</label></td>');
document.write('			<td><input type="text" name="user" title="au moins 4 caract&egrave;res" value="<?php if (isset($_GET['user'])) echo $_GET['user']; ?>"/></td></tr>');
document.write('		<tr><td><label>Mot de passe :</label></td>');
document.write('			<td><input type="password" name="pass" title="au moins 6 caract&egrave;res alphanumeriques"/></td></tr>');
document.write('		<tr><td colspan="2"class="readonly">(comprend au moins 6 caract&egrave;res alphanumeriques.)</td></tr>');
document.write('		<tr><td><label>Confirmer le mot de passe :</label></td>');
document.write('			<td><input type="password" name="confpass"/></td></tr>');
document.write('		<tr><td><label>Nom :</label></td>');
document.write('			<td><input type="text" name="nom" value="<?php if (isset($_GET['nom'])) echo $_GET['nom']; ?>" title="au moins 4 caract&egrave;res"/></td></tr>');
document.write('		<tr><td><label>Pr&eacute;nom :</label></td>');
document.write('			<td><input type="text" name="prenom" value="<?php if (isset($_GET['prenom'])) echo $_GET['prenom']; ?>" title="au moins 4 caract&egrave;res"/></td></tr>');
document.write('		<tr><td><label>Code postal :</label></td>');
document.write('			<td><input type="text" name="cp" title="au format 00000" onkeyup="explicitRegion()"/></td></tr>');
document.write('		<tr class="readonly"><td><label>R&eacute;gion :</label></td>');
document.write('			<td><input type="text" name="region" readonly></td></tr>');
document.write('		<tr><td><label>Email :</label></td>');
document.write('			<td><input type="email" name="mail" placeholder="email@example.com" title="au format ###@###.##"></td></tr>');
document.write('	</table>');
document.write('	<input type="submit" name="soumetre" value="envoyer" /> ');
document.write('</fieldset>');
document.write('</form>		');
</script>
