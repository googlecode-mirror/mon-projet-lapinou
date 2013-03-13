<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/presentation_style.css" />
</head>
<body>
<h1>TEST AFFICHAGE LAPINS</h1>
<?php

require_once("include/sql.php");
connect();
require_once("include/affiche_lapin.inc.php");
$resultat = mysql_query( "SELECT * FROM lapin_lapin;" );

while ($lapin = mysql_fetch_array($resultat) ){
	echo "<p>lapin</p>\n";
	affiche_lapin( $lapin );
}

?>
</body>
</html>
	
