<?php
/////////////////////////////////////
// modifier un lapin               //
/////////////////////////////////////
//session_start();

if( isset($_SESSION['identifiant']) ){
	$user = $_SESSION['identifiant'];
}else {
	header('Location: ../index.php?page=erreur');
	exit(0);
}

require_once("include/sql.php");
if (!connect() ) {
	header('Location: ../index.php?page=erreur');
	exit(0);	
}

//recherche dans la base
$sql = "SELECT * FROM lapin_lapin WHERE id_lapin = ".$_POST['id'].";";
$resultat = mysql_query($sql);	
if ( !$resultat  ){
		disconnect();  //deconnexion MySQL
		header('Location: ../index.php?page=erreur');
		exit(0);
}

$lapin = mysql_fetch_array($resultat);

//date
$date = explode("-",$lapin['agelap']);

?>
<script type="text/javascript" language="Javascript" src="scripts/inscription.js"></script>
<form name="inscLapin" onsubmit="return verif_lapin();" action="include/update_lapin.php" method="post" enctype="multipart/form-data">
<fieldset>
	<legend>Fiche d'identité du lapin(e)</legend>
	<p id="problemes"><?php if( isset( $_GET['mess'] ) ) echo $_GET['mess']; ?></p>
	<!--id lapin -->
	<input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
	<table>
		<tr><td><label>Nom du lapin(e) :</label></td><td><input name="nomlapi" type="text" value="<?php echo $lapin['nomlap'] ?>" readonly/></td></tr>
		<tr><td><label>Date de naissance :</label></td><td><input type="text" name="age" value="<?php echo $date[2]."/".$date[1]."/".$date[0]; ?>" ></td></tr>
		<tr><td colspan="2"><label>Sexe :</label>
			<input type="radio" name="sex" value="m" <?php if( $lapin['sexe']== 'm' ) echo "checked"; ?> />Mâle
			<input type="radio" name="sex" value="f" <?php if( $lapin['sexe']== 'f' ) echo "checked"; ?> />Femelle</td></tr>
		<tr><td><label>Race :</label></td><td>
			<select name="race"/>
				<option value="null">Races proposées</option>
<?php
$races_valides =  array();//tableau associatif pour les races
$races_valides['grande'] = "Grandes Races";
$races_valides['moyenne'] = "Races Moyennes";
$races_valides['petite'] ="Petites Races";
$races_valides['naine'] = "Races Naines";
$races_valides['belier'] = "Races Bélier";
$races_valides['rustique'] = "Races Rustique";
$races_valides['fourrure'] = "Races à Fourrure";
$races_valides['zombie'] = "Races Zombies";
$races_valides['toons'] = "Races Toons";
$races_valides['cretin'] = "Races Crétins";
$races_valides['mutante'] = "Races Mutantes";
$races_valides['cuite'] = "Races Cuites";
$races_valides['indetermine'] = "Races Indéterminées";

foreach( $races_valides as $key => $value) {
	if( $lapin['race']== $key) echo "<option value=\"".$key."\" selected>".$value."</option>\n";
	else echo "<option value=\"".$key."\">".$value."</option>\n";
}
?>			
			</select></td></tr>
		<tr><td><label>Couleur :</label></td><td>
			<select name="couleur">
				<option value="null">Couleurs...</option>
<?php
//pareil pour les couleurs
$couleurs_valides =  array();
$couleurs_valides['unicolore'] ="Unicolore";
$couleurs_valides['panache'] = "Panaché";
$couleurs_valides['mosaique'] = "Mosaïque";
$couleurs_valides['tachete'] = "Tacheté";
$couleurs_valides['agouti'] = "Agouti";
$couleurs_valides['argente'] = "Argenté";

foreach( $couleurs_valides as $key => $value) {
	if( $lapin['couleur']== $key) echo "<option value=\"".$key."\" selected>".$value."</option>\n";
	else echo "<option value=\"".$key."\">".$value."</option>\n";
}
?>				
			</select></td></tr>
		<tr><td><label>Centres d'int&eacute;r&ecirc;t :</label></td><td><input type="text" name="interets" value="<?php echo $lapin['centreInteret']?>"/></td></tr>
	</table>
	<label>Description : </label><textarea rows="3" cols="40" name="desc"><?php echo $lapin['description']?></textarea><p/>
	<label>fichier photo : </label><input type="file" name="photo"></input>
</fieldset>
<input type="submit" value="modifier"/>
<input type="reset" value="effacer" />
</form>
