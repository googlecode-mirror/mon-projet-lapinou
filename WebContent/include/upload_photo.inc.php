<?php
/*****************************************
 * gestion de l'enegistrement des photos *
 *****************************************/
 
 /**
  * enregistrer une photo sur le serveur
  * retourne vrai si OK
  * retourne faux si erreur
  */ 
 function enregistrer_photo( $fichier, $user ){
	 //erreur d'upload
	 if( $fichier['error'] > 0 ) return false; //pb probable : taille de fichier accepte par php
	 //erreur d'extensions
	 $extensions_valides =  array('jpg', 'jpeg', 'gif', 'png' );
	 $extension_upload =  strtolower( substr( strrchr( $fichier['name'], '.') ,1) );
	 if( ! in_array( $extension_upload, $extensions_valides) ) return false;
	 
	 $nouveau_nom = $user.".".$extension_upload;//+ path relatif a include/
	 $chemin = "../img/".$nouveau_nom;
	 
	 //effacer preexistant
	 if( file_exists($chemin) ) unlink($chemin);
	 //redimensionnement
	 $taille = getimagesize( $fichier['tmp_name'] );
	 
	 //TODO
	 
	 //deplacer
	 $resultat = move_uploaded_file( $fichier['tmp_name'], $chemin );
	 if( ! $resultat ) return false;
	 return $nouveau_nom; //nom fichier sinon
 }
	 

?>

