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
	//gestion de l'erreur
	echo "<div class='erreur'>\nLa messagerie n'est pas accessible actuellement.</div>\n";
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

?>
<!-- HTML  -------------------------------------------->
<article>
	<h2>Profil de <?php echo $user; ?></h2>
	<p>Nom : <?php echo $personne['nom']; ?></p>
	<p>Prenom : <?php echo $personne['prenom']; ?></p>
	<p>Localisation : <?php echo $personne['code_postal']." (".$personne['region'].")"; ?></p>
	<p>Mail : <?php echo $personne['mail']; ?></p>
<!-- lapin -->
<!-- TODO -->
<?php
if( $personne['trombine'] ){
	echo "\n<img src='img/".$personne['trombine']."' alt='".$user."' title='".$user."' />";
}
?>
</article>
</body>
</html>

<?php
disconnect();
?>
