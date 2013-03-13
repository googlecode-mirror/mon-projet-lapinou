
<script type="text/javascript" language="Javascript" src="scripts/contact.js"></script>
<!--formulaire de contact-->
<form name="contact" method="post" action="include/envoimail.php" onsubmit="return verif();">
<fieldset>
	<legend>Contactez-nous</legend>
	<p id="problemes"></p>
	<table>
		<tr><td><label>Nom :</label></td>
			<td><input type="text" name="user"/></td></tr>
		<tr><td><label>Email :</label></td>
			<td><input type="email" name="mail" placeholder="email@example.com"></td></tr>
		<tr><td><label>Sujet :</label></td>
			<td><input type="text" name="sujet"/></td></tr>
		<tr><td><label>Message :</label></td>
			<td><textarea rows="10" cols="50" name="message"></textarea></td></tr>
	</table>
	<input type="submit" name="soumettre" value="envoyer"/>
	<input type="reset" name="reset" value="Effacer le formulaire"/>
</fieldset>
</form>		
		
