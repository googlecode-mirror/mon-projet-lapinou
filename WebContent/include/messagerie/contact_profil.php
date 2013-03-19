<?

echo "<pre>";
print_r($_REQUEST);
print_r($_POST);
print_r($_GET);
print_r($_SESSION);
print_r($personne);
echo "</pre>";

?>
<br />
<a href="#" onclick="el=document.getElementById('fdisc');if (el.style.display=='block') el.style.display='none'; else el.style.display='block'; return false;">lancer une discussion</a>
<div id="fdisc" style="display:none;">
<form action="include/messagerie/ouvrir_disc.php" method="POST" name="contact">
<fieldset>
<legend>Nouvelle discussion</legend>
<input type="hidden" name="page" value="profil"></input>
<input type="hidden" name="id_profil" value="<? echo $personne['id_profil']; ?>"></input>
<input type="hidden" name="mid" value="<? echo $_SESSION['mid']; ?>"></input>
auteur : <select name="auteur">
<?

$req="SELECT id_lapin, idLap FROM `${prefixe}lapin` WHERE id_profil = '".$_SESSION['mid']."'";
$res = requeteObj($req);
foreach ($res as $lap)
	echo "<option value='$lap->id_lapin'>$lap->idLap</option>";

?>
</select>
&nbsp;&nbsp;destinataire : <select name="lapin">
<?

$req="SELECT id_lapin, idLap FROM `${prefixe}lapin` WHERE identifiant = '$user'";
$res = requeteObj($req);
foreach ($res as $lap)
	echo "<option value='$lap->id_lapin'>$lap->idLap</option>";

?>
</select>
<br />
titre : <input type="text" name="intitule" value=""></input>
<br />
message : <textarea name="texte" prototype="votre message">
</textarea>
<br />
<input type="submit" name="submit" value="Envoyer"></input>
</fieldset>
</form>
</div>