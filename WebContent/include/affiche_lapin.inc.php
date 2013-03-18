<?php
/*******************************************************************
 * la fonction affiche_lapin :                                     *
 * permet d'afficher une div presentant un lapin                   *
 * le parametre passe est un tableau fourni apres une requete      *
 * $resultat = mysql_query( SELECT * FROM lapin_lapin WHERE ... ); *
 * par $lapin = mysql_fetch_array($resultat);                      *
 *******************************************************************/

session_start();


function affiche_lapin( $lapin ){
	$est_proprietaire=false;
	if( isset( $_SESSION['identifiant'] ) ){
		//on est sur la session du proprietaire
		if( $_SESSION['identifiant'] == $lapin['identifiant'] ) $est_proprietaire=true;
	}
	echo "<div class=\"profil_lapin\">\n";
	echo 	"\t<h2>".$lapin['nomlap']."</h2>\n";
	echo "<table><tr><td>\n";
	echo	"\t<ul>\n";
	echo		"\t\t<li>race : ".$lapin['race']."</li>\n";
	echo		"\t\t<li>age : ".$lapin['agelap']." ans</li>\n";
	echo		"\t\t<li>sexe : ".$lapin['sexe']."</li>\n";
	echo		"\t\t<li>couleur : ".$lapin['couleur']."</li>\n";
	echo	"\t</ul>\n";
	echo "</td><td>\n";
	echo	"\t<img src=\"img/".$lapin['photo']."\" title=\"".$lapin['nomlap']."\"/>\n";
	echo "</td></tr></table>\n";
	echo	"\t<br/>\n";
	echo	"\t<dl><dt>description :</dt><dd>".$lapin['description']."</dd></dl>\n";
	echo	"\t<dl><dt>Centres d'interet : </dt><dd>".$lapin['centreInteret']."</dd></dl>\n";
	echo	"\t<a href=\"index.php?page=profil&user=".$lapin['identifiant']."\">mon proprietaire</a>\n";
	if( $est_proprietaire ){
		echo	"<form method=\"post\" action=\"include/supprimer_lapin.php\" onsubmit=\"return confirmation_supp_lapin();\">\n";
		echo	"<input type=\"hidden\" name=\"id\" value=\"".$lapin['id_lapin']."\" >\n";
		echo	"<input type=\"submit\" value=\"supprimer\" >\n";
		echo	"</form>\n";
		echo	"<form>\n";
		echo	"<input type=\"submit\" value=\"modifier\" >\n";
		echo	"</form>\n";
	}
	echo "</div>\n";
}

?>
<script>
function confirmation_supp_lapin(){
	return confirm('Souhaitez vous reellement supprimer ce lapin ?');
}
</script>

