<?php 
/** Traitement de la requête de recherche d'un partenaire
 * et affichage des résultats triés
 * auteur Dominique le 24/03/2013
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

// la region de l'auteur de la recherche
$sql = "SELECT region FROM lapin_proprietaire WHERE identifiant = '".$_SESSION['identifiant']."';";
$resultat = mysql_query($sql) or die(mysql_error());	
$region = mysql_fetch_array($resultat)['region'];
print_r($region);
 
$races=array("grande","moyenne","petite","naine","belier","rustique","fourrure","zombie","toons","cretin","mutante","cuite","indetermine");
$couleurs=array("unicolore","panache","mosaique","tachete","agouti","argente");

//----------------> TODO vérifier l'intégrité du formulaire
$post = $_POST;	

// construction de la requête 
// Case pour les races
$sqlrace = "CASE race ";
for($i=0; $i < count($races);$i++) {
	$sqlrace .= "WHEN '".$races[$i]."' THEN ".$post[$races[$i]]." \n ";
}
$sqlrace .= " END as srace ";

// case pour les couleurs
$sqlcoul = "CASE couleur ";
for($i=0; $i < count($couleurs);$i++) {
	$sqlcoul .= "WHEN '".$couleurs[$i]."' THEN ".$post[$couleurs[$i]]." \n ";
}
$sqlcoul .= " END as scoul ";;

// assemblage de la requête
$sql = "SELECT nomlap, (srace + scoul) as score, identifiant, region ".
		"FROM ( SELECT nomlap, ".$sqlrace.", ".$sqlcoul.", sexe, identifiant ".
		"       FROM lapin_lapin ) lapiscore NATURAL JOIN lapin_proprietaire".// ON lapin_lapin.identifiant = lapin_proprietaire.identifiant ".
		"   WHERE ".((isset($post['sex']) AND $post['sex']<>"") ? " sexe = '".$post['sex']."' AND " : "").
		((isset($post['region']) AND $post['region']="1") ? " region = '".$region."' AND " : "").
		" 		identifiant <> '".$_SESSION['identifiant']."' ".
		" ORDER BY score DESC ;";


$resultat = mysql_query($sql) or die($sql.mysql_error());

?>
<fieldset>
	<legend>Trouves tu ton âme soeur ?</legend><br/>
<?php		
while ($lapin = mysql_fetch_array($resultat) ){ 
	echo "<a href=index.php?page=lapin&lapin=".$lapin['nomlap']."&proprio=".$lapin['identifiant'].">".$lapin['nomlap']."</a><br/>";
	}				
?>
</fieldset>
