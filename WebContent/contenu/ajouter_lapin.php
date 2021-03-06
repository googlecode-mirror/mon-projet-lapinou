<?php
/////////////////////////////////////
// formulaire pour                 //
// ajouter un lapin                //
/////////////////////////////////////
//session_start();

if( isset($_SESSION['identifiant']) ){
	$user = $_SESSION['identifiant'];
}else {
	header('Location: ../index.php?page=erreur');
	exit(0);
}

?>
<!-- le formulaire -->
<script type="text/javascript" language="Javascript" src="scripts/inscription.js"></script>
<form name="inscLapin" onsubmit="return verif_lapin();" action="include/inscrire_lapin.inc.php" method="post" enctype="multipart/form-data">
<fieldset>
	<legend>Fiche d'identité du lapin(e)</legend>
	<p id="problemes"><?php if( isset( $_GET['mess'] ) ) echo $_GET['mess']; ?></p>
	<table>
		<tr><td><label>Nom du lapin(e) :</label></td><td><input type="text" name="nomlapi"/></td></tr>
		<tr><td><label>Date de naissance :</label></td><td><input type="text" name="age" placeholder="30/01/2012"></td></tr>
		<tr><td colspan="2"><label>Sexe :</label>
			<input type="radio" name="sex" value="m"/>Mâle
			<input type="radio" name="sex" value="f"/>Femelle</td></tr>
		<tr><td><label>Race :</label></td><td>
			<select name="race"/>
				<option value="null">Races proposées</option>
				<option value="grande">Grandes Races</option>
				<option value="moyenne">Races Moyennes</option>
				<option value="petite">Petites Races</option>
				<option value="naine">Races Naines</option>
				<option value="belier">Races Bélier</option>
				<option value="rustique">Races Rustique</option>
				<option value="fourrure">Races à Fourrure</option>
				<option value="zombie">Races Zombies</option>
				<option value="toons">Races Toons</option>
				<option value="cretin">Races Crétins</option>
				<option value="mutante">Races Mutantes</option>
				<option value="cuite">Races Cuites</option>
				<option value="indetermine">Races Indéterminées</option>
			</select></td></tr>
		<tr><td><label>Couleur :</label></td><td>
			<select name="couleur">
				<option value="null">Couleurs...</option>
				<option value="unicolore">Unicolore</option>
				<option value="panache">Panaché</option>
				<option value="mosaique">Mosaïque</option>
				<option value="tachete">Tacheté</option>
				<option value="agouti">Agouti</option>
				<option value="argente">Argenté</option>
			</select></td></tr>
		<tr><td><label>Centres d'int&eacute;r&ecirc;t :</label></td><td><input type="text" name="interets"/></td></tr>
	</table>
	<label>Description : </label><textarea rows="3" cols="40" name="desc"></textarea><p/>
	<label>fichier photo : </label><input type="file" name="photo"></input>
</fieldset>
<input type="submit" value="inscrire"/>
<input type="reset" value="effacer" />
</form>
