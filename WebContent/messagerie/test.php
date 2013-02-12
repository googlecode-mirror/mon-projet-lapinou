<?php
//echo phpinfo();
echo "ok";

print_r($_REQUEST);
print_r($_POST);
print_r($_GET);

include_once "include/sql.php";

function echec($message) {
//!!! à développer
if (isset($bdd))
  mysql_close($bdd);
die ("<div><dl><dt>Echec</dt><dd>$message</dd></dl></div>");	
}

//récupérer les données transmises
$lapin=$_REQUEST["lapin"];
$intitule=$_REQUEST["intitule"];
$texte=$_REQUEST["texte"];
$dest=$_REQUEST["id_profil"];
$auteur=$_REQUEST["sid"];	//!!! il s'agit en fait de l'id_profil transmis par la session

echo "contrôle";



?>
