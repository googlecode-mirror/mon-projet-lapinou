<?php
/////////////////////////////////////
// afficher un profil proprietaire //
/////////////////////////////////////

/********************
 * variables utiles *
 ********************/
if (!isset($_GET['user'])) {
	header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
	exit(0);
}
$user = $_GET['user']; //TODO si vide ?????????????????

require_once("include/connexion.inc.php");
	if (!connect()) {
	//gestion de l'erreur
		echo "<div class='erreur'>\nLa messagerie n'est pas accessible actuellement.</div>\n";
		exit;
	}
//connect(); //connexion MySQL

//recherche de la personne		
$sql = "SELECT * FROM proprietaire WHERE identifiant = '".$user."';";
$resultat = mysql_query($sql);	
if ( !$resultat  ){
		echo $sql." \nun probleme s'est produit.".mysql_error();
		disconnect();  //deconnexion MySQL
	}	
$personne = mysql_fetch_array($resultat);
/*
if( ! $personne ) { // user n'existe pas dans la base
	header('Location: ../index.php?page=erreur');	//TODO une page erreur <---------------------------------------
	exit(0);
}
*/
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
if( $personne['photo']){
	echo "\n<img src='".$personne['photo']."' alt='".$user."' title='".$user."' />";

}else{
	echo "\n<form name='ajout_photo' enctype='multipart/form-data' action='include/upload_photo.inc.php' method='post'>";
	echo "\n<fieldset><legend>ajouter une photo</legend><table>";
	echo "\n<tr><td><td><label>fichier</label><input type='file' name='trombine'></input></td>";
	echo "\n<tr><td></td><td><input type='submit' value='Envoyer'/></td>";
	echo "\n</table></fieldset></form>";
}
?>
</article>
</body>
</html>

<?php
disconnect();
?>
