<?php
/**
 * Dominique PAYANT le 24-03-2013
 * Recherche d'un partenaire selon des goûts 
 * (formulaire sous forme de case à cocher)
 * Calcul un score pour les lapins et propose les résultats triés
 * 
 */

// du code copié partiellement depuis profil.php (auteur Cyril) 
require_once("include/sql.php");
if (!connect() ) {
	header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
	exit(0);
}

$lapin = $_GET['lapin'];

$sql = "SELECT * FROM lapin_lapin WHERE identifiant = '".$_SESSION['identifiant']."' AND nomlap = '".$lapin."';";
$resultat = mysql_query($sql);	
if ( !$resultat  ){ // le lapin n'appartient à la personne qui tente d'accéder à la page
		disconnect();  //deconnexion MySQL
		header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
		exit(0);
}

$profil = mysql_fetch_array($resultat);



?>

<form name="rencontre" action="index.php?page=rencontrer" method="post">
<fieldset>
	<legend><h1><?php echo $lapin; ?> aime :</h1></legend>
	<table>
		<tr><td style="width: 30%"><label><h2>Sexe</h2></label></td>
			<td><input type="radio" name="sex" value="f"/></td><td>Les filles</td>
			<td><input type="radio" name="sex" value="m"/></td><td>Les garçons</td>
		</tr>
		<tr><td style="width: 30%"><label><h2>Région</h2></label></td>
			<td><input type="radio" name="region" value="1"/></td><td>Sa région</td>
			<td><input type="radio" name="region" value="2"/></td><td>La France</td>
		</tr>
		<tr><td><label><h2>Race</h2></label></td><td>pas du tout</td><td>un peu</td><td>beaucoup</td><td>à la folie</td></tr>
<?php 
	$liste=array("grande","moyenne","petite","naine","belier","rustique","fourrure","zombie","toons","cretin","mutante","cuite","indetermine");
	for($i=0, $m=count($liste);$i<$m;$i++) {
		echo '<tr><td>'.$liste[$i].'</td>'.
			'<td><input type="radio" name="'.$liste[$i].'" value="-3"></td>'.
			'<td><input type="radio" name="'.$liste[$i].'" value="0" checked="checked"></td>'.
			'<td><input type="radio" name="'.$liste[$i].'" value="1"></td>'.
			'<td><input type="radio" name="'.$liste[$i].'" value="2"></td></tr>';
	}
?>
		<tr><td><label><h2>Couleur</h2></label></td><td>pas du tout</td><td>un peu</td><td>beaucoup</td><td>à la folie</td>
<?php 
	$liste=array("unicolore","panache","mosaique","tachete","agouti","argente");
	for($i=0, $m=count($liste);$i<$m;$i++) {
		echo '<tr><td>'.$liste[$i].'</td>'.
			'<td><input type="radio" name="'.$liste[$i].'" value="-3"></td>'.
			'<td><input type="radio" name="'.$liste[$i].'" value="0" checked="checked"></td>'.
			'<td><input type="radio" name="'.$liste[$i].'" value="1"></td>'.
			'<td><input type="radio" name="'.$liste[$i].'" value="2"></td></tr>';
	}
?>
		
	</table>
	<input type="submit" value="chercher"/>
</fieldset>
</form>
