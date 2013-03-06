<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>LAPI.NET</title>
	<meta name="author" content="master CCI">
	<meta charset="utf-8">
	<meta name="description" content="site de rencontre des lapinous">
	<meta name="keywords" content="lapins rencontre date amitie amour">
	<link rel="stylesheet" type="text/css" href="styles/presentation_style.css" />
	<script type="text/javascript" language="Javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" language="Javascript" src="scripts/jquery.min.js"></script>
	<script type="text/javascript" language="Javascript" src="scripts/chat.js"></script>
</head>
<body>
<?php 
	include("include/chat.inc.php");
?>
<div class="corps">
<header>
<div class="bandeau">
<br>
<img src="img/LOGO_new.png" alt="LAPI.NET" title="LAPI.NET"></img>
<br>
</div>
</header>

<ul id="menu">
         <li>
                <a href="index.php">Accueil</a>
        </li>
        <li>
                <a href="#">Membres</a>
                <ul>
                        <li><a href="index.php?page=inscription">Inscription</a></li>
			<li><a href="index.php?page=messagerie">Messagerie</a></li>
			<li><a href="index.php?page=profil">Profil</a></li>
                </ul>
        </li>
	 <li>
                <a href="#">Amis</a>
                
        </li>
        <li>
                <a href="#">Recherche</a>
                
        </li>
         
        <li>
                <a href="#">Forum</a>
                
        </li>
         
        <li>
                <a href="#">Génie Logiciel</a>
                <ul>
                        <li><a href="GL/acceuil.htm">Sommaire</a></li>
                        <li><a href="GL/plan.htm">Plan de développement</a></li>
                        <li><a href="GL/besoin.htm">Définition des besoins</a></li>
                        <li><a href="GL/conception.htm">Dossier de conception</a></li>
                        <li><a href="GL/prototype.htm">Prototype</a></li>
			<li><a href="GL/presentation.htm">Documents de présentation</a></li>
                </ul>
        </li>
	<li>
                <a href="#">Plus</a>
                <ul>
                        <li><a href="#">Contactez-nous</a></li>
                        <li><a href="#">Qui sommes-nous ?</a></li>
                        <li><a href="#">Partenaires</a></li>
                        <li><a href="#">Administration du site</a></li>
                        
                </ul>
        </li>
         
         
</ul>

<br class="retour" />
<br />

<section id="contenu">
	<?php 
		include("include/cadre.inc.php");
	?>
</section>

<div id="droite">
<div id="connexion">
	<?php 
	include("contenu/login.php") ?>
</div>
<!--  nouveaux -->
	<?php 
		include("include/nouveaux.php");
	?>
<!-- -->
<?php
	if( isset($_SESSION['identifiant']) ){ //initialise ailleurs (connexion membre)
		echo "<div id=\"tchat\"><p> tchat</p>";
		echo "<img src=\"img/lapiphone.png\" /><br/>";
		echo "<button type=\"button\" onclick=\"javascript:montrer_lapiphone();\">afficher</button><br/>";
		echo "<button type=\"button\" onclick=\"javascript:cacher_lapiphone();\">masquer</button>";
		echo "</div>";
	}
?>
</div>
<br class="retour" />

<br />
<br />

<footer>

	<img src="img/barre-lapin.gif"  alt="barre" title="barre lapin"/><img>
	<br>
	<a href="index.php" target="_blank" title="Aller à l'accueil">Accueil</a>
	<span class="portal-pipe">|</span>
	<a href="#" target="_blank" title="Contactez-nous">Contact</a>
	<span class="portal-pipe">|</span>
	<a href="#" target="_blank" title="Qui sommes nous">Qui sommes-nous ?</a>
	<span class="portal-pipe">|</span>
	<a href="#" target="_blank" title="Administration">Administration</a>

</footer>
</div>

</body>
</html>

