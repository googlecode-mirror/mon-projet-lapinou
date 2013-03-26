<?
/*************************************************
 *	Gère l'affichage du formulaire d'ouverture	**
 *	d'une discussion avec un autre membre.		**
 * 												**
 * 		Florent Arnould	-	19 mars 2013		**
 *************************************************/
?>
<br />
<script>
function verif_form() {
	el=document.reponse;
	if (el.intitule.value.length>64) {
//		alert("Le titre est trop long.");
		document.erreur.innerHTML="Le titre est trop long.";
		return false;
	} else {
		return true;
	}
}
</script>
<a href="#" onclick="el=document.getElementById('fdisc');if (el.style.display=='block') el.style.display='none'; else el.style.display='block'; return false;">lancer une discussion</a>
<div id="fdisc" style="display:none;">
<form action="include/messagerie/ouvrir_disc.php" method="POST" name="contact" onsubmit="return verif_form()">
	<fieldset>
		<legend>Nouvelle discussion</legend>
		<input type="hidden" name="page" value="profil"></input>
		<input type="hidden" name="id_profil" value="<? echo $personne['id_profil']; ?>"></input>
		<input type="hidden" name="mid" value="<? echo $_SESSION['mid']; ?>"></input>
auteur : <select name="auteur">
<?

//recherche des lapins du membre connecté
$req="SELECT id_lapin, nomlap FROM `${prefixe}lapin` WHERE id_profil = '".$_SESSION['mid']."'";
$res = requeteObj($req);
//affichage sous forme d'options html
foreach ($res as $lap)
	echo "<option value='$lap->id_lapin'>$lap->nomlap</option>";

//fermer et passer au destinataire
?>
</select>
&nbsp;&nbsp;destinataire : <select name="lapin">
<?

//recherche des lapins du profil consulté
$req="SELECT id_lapin, nomlap FROM `${prefixe}lapin` WHERE identifiant = '$user'";
$res = requeteObj($req);
//affichage sous forme d'options html
foreach ($res as $lap)
	echo "<option value='$lap->id_lapin'>$lap->nomlap</option>";

//fermer et passer au contenu du message
?>
</select>
<br />
<span id="erreur"></span>
titre : <input type="text" name="intitule" value="" maxlength="64"></input>
<br />
message : <textarea name="texte" prototype="votre message">
</textarea>
<br />
<input type="submit" name="submit" value="Envoyer"></input>
</fieldset>
</form>
</div>