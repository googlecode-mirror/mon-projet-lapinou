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
$resultat = mysql_query($sql);	
$region = mysql_fetch_array($resultat);
$region = $region['region'];
//print_r($region);
 
$races=array("grande","moyenne","petite","naine","belier","rustique","fourrure","zombie","toons","cretin","mutante","cuite","indetermine");
$couleurs=array("unicolore","panache","mosaique","tachete","agouti","argente");

//----------------> TODO vérifier l'intégrité du formulaire
// à minima, protection contre les injections sql des champs insérés dans la base à l'aide de mysql_real_escape_string
$post = $_POST;	


// construction de la requête 
// Case pour les races
$sqlrace = "CASE race ";
for($i=0; $i < count($races);$i++) {
	$sqlrace .= "WHEN '".$races[$i]."' THEN ".mysql_real_escape_string($post[$races[$i]])." \n ";
}
$sqlrace .= " END as srace ";

// case pour les couleurs
$sqlcoul = "CASE couleur ";
for($i=0; $i < count($couleurs);$i++) {
	$sqlcoul .= "WHEN '".$couleurs[$i]."' THEN ".mysql_real_escape_string($post[$couleurs[$i]])." \n ";
}
$sqlcoul .= " END as scoul ";;

// assemblage de la requête
$sql = "SELECT *, (srace + scoul) as score ".
		"FROM ( SELECT *, ".$sqlrace.", ".$sqlcoul.
		"       FROM lapin_lapin ) lapiscore NATURAL JOIN lapin_proprietaire".// ON lapin_lapin.identifiant = lapin_proprietaire.identifiant ".
		"   WHERE ".((isset($post['sex']) AND $post['sex']<>"") ? " sexe = '".mysql_real_escape_string($post['sex'])."' AND " : "").
		((isset($post['region']) AND $post['region']="1") ? " region = '".$region."' AND " : "").
		" 		identifiant <> '".$_SESSION['identifiant']."' ".
		" ORDER BY score DESC ;";


$resultat = mysql_query($sql);


require_once("include/affiche_lapin.inc.php");


?>
<fieldset>
	<legend><h1>Trouves tu ton âme soeur ?</h1></legend><br/>
	<div><table>
<?php		
$compteur = 0;

//affichage en tableau a 2 colonnes
while ($lapin = mysql_fetch_array($resultat) ){
	if( $compteur % 2 == 0 ) {
		echo "<tr><td>";
		affiche_lapin( $lapin );
		echo "</td>";
	}else {
		echo "<td>";
		affiche_lapin( $lapin );
		echo "</td></tr>";
	}
	$compteur++;
}
if( $compteur % 2 == 1 ) 
	echo "</tr>";
?>
	</table></div>
</fieldset>
