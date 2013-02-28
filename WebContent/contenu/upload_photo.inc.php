<?php
session_start();

if( isset($_SESSION['identifiant']) ){ //initialise ailleurs
	$id = $_SESSION['identifiant'];
}else{
	//sinon erreur
	$id="toto";
}
	

/////////////////////////////////////////////////
// ajout d'une photo sur la table proprietaire //
/////////////////////////////////////////////////
if(isset($_FILES['trombine']))
{ 
	$fichier = "../img/".$id.'.jpg'; //attention chmod
     
	//On fait un tableau contenant les extensions autorisées.
	$extensions = array('.jpg', '.jpeg');
	// récupère la partie de la chaine à partir du dernier . pour connaître l'extension.
	$extension = strrchr($_FILES['trombine']['name'], '.');
	//Ensuite on teste
	if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
	{
		$erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
	}
	// taille maximum (en octets)
	$taille_maxi = 100000;
	//Taille du fichier
	$taille = filesize($_FILES['trombine']['tmp_name']);
	if($taille>$taille_maxi)
	{
		$erreur = 'Le fichier est trop gros...';
	}     
     
     
     if(move_uploaded_file($_FILES['trombine']['tmp_name'], $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
          echo 'Upload effectué avec succès !';
     }
     else //Sinon (la fonction renvoie FALSE).
     {
          echo 'Echec de l\'upload !';
     }
}


echo "ok";


?>
