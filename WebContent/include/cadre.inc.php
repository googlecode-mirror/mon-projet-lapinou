<?php

	// switch pour le contenu de la section "contenu"
if (!isset($_GET['page'])) include(page par défaut à définir);
else {
	$redirige = false;
	// index.php?page='login'
	if ($_GET['page']=='login') {
		include(login.php);
		$redirige = true;
	}
	
	// à compléter
	
	
	
	
	if (!$redirige) include(page par défaut à définir);
}

?>
