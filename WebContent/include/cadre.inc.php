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
		include("include/messagerie.php");
		$redirige = true;
	}
	// index.php?page='profil'
	if ($_GET['page']=='profil') {
		include("contenu/profil.php");
		$redirige = true;
	}
		// index.php?page='Qui sommes-nous'
	if ($_GET['page']=='identite-groupe.php') {
		include("contenu/identite-groupe.php");
		$redirige = true;
	}
	// à compléter
	
	
	
	
	if (!$redirige) include("contenu/accueil.php");
}

?>
