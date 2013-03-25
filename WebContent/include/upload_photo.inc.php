<?php
/******************************************
 * gestion de l'enregistrement des photos *
 ******************************************/
 
 /**
  * enregistrer une photo sur le serveur
  * $fichier = $_FILES['nomdefichier']
  * $user = nouveau nom -> img/user#####.jpg
  * retourne le nom si OK
  * retourne faux si erreur
  */ 
function enregistrer_photo( $fichier, $user ){
	//erreur d'upload
	if( $fichier['error'] > 0 ) return false; //pb probable : taille de fichier accepte par php
	//erreur d'extensions
	$extensions_valides =  array('jpg', 'jpeg', 'gif',  'png' );
	$extension_upload =  strtolower( substr( strrchr( $fichier['name'], '.') ,1) );
	if( ! in_array( $extension_upload, $extensions_valides) ) return false;
	
	$nouveau_nom = $user.time().".".$extension_upload;//+ path relatif a include/
	$chemin = "../img/".$nouveau_nom;
	 
	//effacer preexistant
	if( file_exists($chemin) ) unlink($chemin);
	//redimensionnement
	//jpeg /jpg
	$mime_jpg = array( 'image/jpeg', 'image/pjpeg', 'image/jpg' );
	$mime_png = array('image/png', 'image/x-png' );
	$mime_gif = array('image/gif');
	 

	$taille = getimagesize( $fichier['tmp_name'] );

	if($taille[1] > 200){
		$hauteur_finale=200;
		$largeur_finale = ( ($taille[0] * (200.0 / $taille[1])) );
	}else {
		$largeur_finale = $taille[0];
		$hauteur_finale= $taille[1];
	}
		
	//try{
		if( in_array( $taille['mime'], $mime_jpg ) ){ //images jprg
			$ImageChoisie = imagecreatefromjpeg($fichier['tmp_name']) or die ();
			$NouvelleImage = imagecreatetruecolor($largeur_finale , $hauteur_finale) or die ();
			 
			imagecopyresampled($NouvelleImage , $ImageChoisie  , 0,0, 0,0, $largeur_finale, $hauteur_finale, $taille[0],$taille[1]);
			imagejpeg($NouvelleImage , $chemin);
			
			imagedestroy($ImageChoisie);
		}else if( in_array( $taille['mime'], $mime_png ) ){ //images png
			$ImageChoisie = imagecreatefrompng($fichier['tmp_name']) or die ();
			$NouvelleImage = imagecreatetruecolor($largeur_finale , $hauteur_finale) or die ();
			 
			imagecopyresampled($NouvelleImage , $ImageChoisie  , 0,0, 0,0, $largeur_finale, $hauteur_finale, $taille[0],$taille[1]);
			imagepng($NouvelleImage , $chemin);
			
			imagedestroy($ImageChoisie);
		}else if( in_array( $taille['mime'], $mime_png ) ){ //images gif
			$ImageChoisie = imagecreatefromgif($fichier['tmp_name']) or die ();
			$NouvelleImage = imagecreatetruecolor($largeur_finale , $hauteur_finale) or die ();
			 
			imagecopyresampled($NouvelleImage , $ImageChoisie  , 0,0, 0,0, $largeur_finale, $hauteur_finale, $taille[0],$taille[1]);
			imagegif($NouvelleImage , $chemin);
			
			imagedestroy($ImageChoisie);		
		}else{
			return false;
		}
	//}catch( Exception $e ){
		//si on voulait deplacer malgre tout sans redimensionner
		/**
		$resultat = move_uploaded_file( $fichier['tmp_name'], $chemin );
		
		if( ! $resultat ) */
	//	return false;
	//}
	return $nouveau_nom; //nom fichier sinon
}
?>
