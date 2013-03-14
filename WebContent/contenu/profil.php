<?php
/////////////////////////////////////
// afficher un profil proprietaire //
/////////////////////////////////////
session_start();
/********************
 * variables utiles *
 ********************/
if (!isset($_GET['user']) || $_GET['user'] == "") {
	if( isset($_SESSION['identifiant']) ){
		$user = $_SESSION['identifiant'];
		$prive=true;
	}else {
		header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
		exit(0);
	}
}else{
	$user = $_GET['user'];
	if( $user == $_SESSION['identifiant'] ) $prive = true;
	else $prive = false;
}
require_once("include/sql.php");
if (!connect() ) {
	header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
	exit(0);
}
//recherche de la personne		
$sql = "SELECT * FROM lapin_proprietaire WHERE identifiant = '".$user."';";
$resultat = mysql_query($sql);	
if ( !$resultat  ){
		disconnect();  //deconnexion MySQL
		header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
		exit(0);
}	
$personne = mysql_fetch_array($resultat);

echo "<script type=\"text/javascript\" src=\"scripts/inscription.js\"></script>\n";
//debut de la section.
echo "<article>\n";
//titre
echo "<h2>Profil de ".$user."</h2>\n";
//photo
if( $personne['trombine'] )	echo "\n<img src='img/".$personne['trombine']."' alt='".$user."' title='".$user."' />\n";

if( $prive && $_GET['modifier'] ){
	echo "<p id=\"problemes\"></p>\n";//messages d'erreur
	echo "<form name =\"inscription\" action=\"include/modifier_profil.inc.php\" method=\"post\" 
		enctype=\"multipart/form-data\" onsubmit=\"return verif_modif()\" >\n"; //TODO <--------------------------
	echo "<input type=\"hidden\" name=\"user\" value=\"".$user."\" />\n"; //permet de recharger le meme profil
	//modifier la photo
	echo "<label>fichier photo : </label><input type=\"file\" name=\"trombine\"></input><p/>\n";
	//modifier la localisation
	echo "<label>Code postal :</label><input type=\"text\" name=\"cp\" title=\"au format 00000\" 
		onkeyup=\"explicitRegion()\" value=\"".$personne['code_postal']."\"/><br/>\n";
	echo "<p class=\"readonly\"><label>R&eacute;gion :</label><input type=\"text\" name=\"region\" readonly></p>\n";
}else echo "<p>Localisation : ".$personne['code_postal']." (".$personne['region'].")</p>\n";

if( $prive ){ //champs prives
	echo "<p>Nom : ".$personne['nom']."</p>\n";
	echo "<p>Prenom : ".$personne['prenom']."</p>\n";
	if( $_GET['modifier'] ){
		//modifier mail
		echo "<label>Email :</label><input type=\"email\" name=\"mail\" value=\"".$personne['mail']."\" title=\"au format ###@###.##\">\n";
		echo "<p></p>\n";
		//valider
		echo "<input type=\"submit\" name=\"submit\" value=\"valider\" />\n";		
		echo "</form>\n";
	}else{
		 echo "<p>Mail : ".$personne['mail']."</p>\n";

	//bouton modifier	
		echo "<form name =\"modifier\" action=\"".$_SERVER['PHP_SELF']."\" method=\"get\" >\n";
		echo "<input type=\"submit\" name=\"modifier\" value=\"modifier\" />\n"; //mettre en post avec des $_request -> pb avec cadre ?
		echo "<input type=\"hidden\" name=\"user\" value=\"".$user."\" />\n"; //permet de recharger le meme profil
		echo "<input type=\"hidden\" name=\"page\" value=\"profil\" />\n"; //permet de recharger le meme profil
		echo "</form>\n";
	}
	
	//bouton ajouter un lapin	
	echo "<form name =\"add_lapin\" action=\"index.php?page=ajouter_lapin\" method=\"post\" >\n"; //TODO <--------------------------
	echo "<input type=\"submit\" name=\"ajout\" value=\"ajout de lapin\" />\n";
	echo "</form>\n";
}else if( isset($_SESSION['identifiant']) ){ //profil public, etat connecte : proposer comme ami
	echo "<a href=\"\">ajouter aux amis</a>";
}
//afficher les lapins ici

?>
</article>
<?php
disconnect();
if( $prive && $_GET['modifier'] ){
	echo "<script>document.body.onload = function(){explicitRegion();};</script>\n";// affichage de la region au chargement
}
?>
