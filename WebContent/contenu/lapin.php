<?php 
/** Profil d'un lapin
 * accessible depuis une recherche par ex : obsolète
 */

/********************
 * variables utiles *
 ********************/
if (!isset($_GET['proprio']) || $_GET['proprio'] == "" || !isset($_GET['lapin']) || $_GET['lapin']=="") { 
		//print_r($_GET);
		header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
		exit(0);
	}

$proprio = $_GET['proprio']; // TODO protéger contre les injections SQL : quelles fonctions disponibles sous free finalement ?
$lapin = $_GET['lapin'];

require_once("include/sql.php");
if (!connect() ) {
	//echo "erreur connexion";
	header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
	exit(0);
}

?>
<article>
<div><table>

<?php

require_once("include/affiche_lapin.inc.php");
$resultat = mysql_query( "SELECT * FROM lapin_lapin WHERE identifiant = '".$proprio."' AND nomlap = '".$lapin."';" );
$lapin = mysql_fetch_array($resultat);
affiche_lapin( $lapin );
?>
		
</article>
<?php
disconnect();

?>

 
 
?>
