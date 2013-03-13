<?php
/*******************************************************************
 * la fonction affiche_lapin :                                     *
 * permet d'afficher une div presentant un lapin                   *
 * le parametre passe est un tableau fourni apres une requete      *
 * $resultat = mysql_query( SELECT * FROM lapin_lapin WHERE ... ); *
 * par $lapin = mysql_fetch_array($resultat);                      *
 *******************************************************************/

function affiche_lapin( $lapin ){
echo "<div class=\"profil_lapin\">\n";
echo 	"\t<h2>".$lapin['nomlap']."</h2>\n";
echo	"\t<img src=\"img/".$lapin['photo']."\" title=\"".$lapin['nomlap']."\" alt =\"".$lapin['nomlap']."\" />\n";
echo	"\t<ul>\n";
echo		"\t\t<li>race : ".$lapin['race']."</li>\n";
echo		"\t\t<li>age : ".$lapin['agelap']." ans</li>\n";
echo		"\t\t<li>sexe : ".$lapin['sexe']."</li>\n";
echo		"\t\t<li>couleur : ".$lapin['couleur']."</li>\n";
echo	"\t</ul>\n";
echo	"\t<br class=\"retour\" />\n";
echo	"\t<dl><dt>description :</dt><dd>".$lapin['description']."</dd></dl>\n";
echo	"\t<dl><dt>Centres d'interet : </dt><dd>".$lapin['centreInteret']."</dd></dl>\n";
echo	"\t<a href=\"index.php?page=prifil&user=".$lapin['identifiant'].">mon proprietaire</a>\n";
echo "</div>\n";
}

/***********************************************
 * ici la meme fonction pour les proprietaires *
 * donc avec des champs modifiables            *
 ***********************************************/
?>
