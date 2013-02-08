<?php

$serveur=$_SERVER["SERVER_NAME"];
if ($serveur=="arnould.f.free.fr") {
	$hote="sql.free.fr";
	$user="arnould.f";
	$passe="L4p1nG4r0u";
	$base="arnould.f";
} else 
	if ($serveur=="webetu") {
		$hote="dbhost";
		$user="o2123859";
		$passe="o2123859";
		$base="bd_o2123859";
	} else 
		if ($serveur=="localhost") {
			$hote="localhost";
			$user="root";
			$passe="";
			$base="lapinou";
		} else {
			die("Pas d'information de connexion pour ce serveur !");
		}

	$bd_con =  mysql_connect($hote,$user,$passe);
	if ($bd_con===null)
		die("la connexion à $hote n'a pas pu avoir lieu.");
	else 
		$bdd=mysql_select_db($base);
echo $bdd;
				
	echo phpinfo();

?>