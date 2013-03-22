<?php

	// switch pour le contenu de la section "contenu"
if (!isset($_GET['page'])) include("contenu/accueil.php");
else {
	$redirige = false;
	
	// index.php?page='login'
	if ($_GET['page']=='login') {
		include("contenu/login.php");
		$redirige = true;
	}
	// index.php?page='inscription'
	if ($_GET['page']=='inscription') {
		include("contenu/inscription.php");
		$redirige = true;
	}
	// index.php?page='messagerie'
	if ($_GET['page']=='messagerie') {
		//!!! à priori devrait être inclu dans une page de profil et pas être une page en soi
		include("include/messagerie/messagerie.php");
		$redirige = true;
	}
	// index.php?page='profil'
	if ($_GET['page']=='profil') {
		include("contenu/profil.php");
		$redirige = true;
	}
	// index.php?page='contactez-nous'
	if ($_GET['page']=='contact') {
		include("contenu/contact.php");
		$redirige = true;
	}
		// index.php?page='Qui sommes-nous'
	if ($_GET['page']=='identite-groupe') {
		include("contenu/identite-groupe.php");
		$redirige = true;
	}
	// index.php?page='Liens'
	if ($_GET['page']=='liens') {
		include("contenu/liens.php");
		$redirige = true;
	}
	// index.php?page='ajouter_lapin'
	if ($_GET['page']=='ajouter_lapin') {
		include("contenu/ajouter_lapin.php");
		$redirige = true;
	}
	// index.php?page='recherche'
	if ($_GET['page']=='recherche') {
		include("contenu/chercher.html");
		$redirige = true;
	}
	// index.php?page='trouve'
	if ($_GET['page']=='trouve') {
		include("include/recherche.php");
		$redirige = true;
	}
		// index.php?page='ami'
	if ($_GET['page']=='amis') {
		include("contenu/amis.php");
		$redirige = true;
	}
	// index.php?page='erreur'
	if ($_GET['page']=='erreur') {
		include("contenu/erreur.php");
		$redirige = true;
	}
	// à compléter
	
	
	
	
	if (!$redirige) include("contenu/accueil.php");
}

?>
