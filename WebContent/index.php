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
</head>
<body>
<div class="corps">
<header>
<div class="bandeau">
<br>
	<img src="img/Lapi-bisous.jpg" width="100" alt="Logo site" title="Logo lapin"/><img>
<center><h1>Lapi.net</h1></center>
</div>
</header>

<ul id="menu">
         <li>
                <a href="#">Accueil</a>
        </li>
        <li>
                <a href="#">Membres</a>
                <ul>
                        <li><a href="#">Connexion</a></li>
                        <li><a href="#">Inscription</a></li>
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
	<h2>Présentation</h2>
<br /> 
<p>
<div class="colonne"> 
	<h3>Accueil</h3>
	<p>Tu es seul ? Tu aimerais trouver un ou une lapine qui te correspond ? Ne cherches pas plus loin, pleins d'autres lapins sont inscrits sur ce site. Plus besoin de chercher
ailleurs. En t'inscrivant sur ce site, tu pourras faire la rencontre d'autres lapins qui te correspond et qui ont les mêmes attentes que toi.
Tu pourras, entre autre, faire la connaissance de la ou du lapin de ta vie... alors n'ésites plus, cliques et profites...</p>
</div>
<div class="colonne"> 
	<h3>les news</h3> 
	<p>***Site en construction*** il te faudra patienter un peu pour avoir des nouvelles. En attendant.... Happy Valentin !!!</p>
</div>
<div class="colonne"> 
	<h3>les événements</h3>
	<p>***Site en construction*** Les prochains événements seront indiqués alors n'hésites pas à consulter le site fréquemment.</p>
</p>
</section>

<div id="droite">
<div id="connexion">
	<form>
	<fieldset>
		<legend>Connexion</legend>
		<label>Nom d'utilisateur :</label><input type="text" name="user"> 	
		<br>
		<label>Password :</label><input type="password" name="pass">
		<br>
		<input type="submit" value="Connexion" />
	</form>
</div>
<div id="messagerie"><p> messagerie</p>
</div>
<div id="tchat"><p> tchat</p>
</div>
</div>

<br class="retour" />

<br />
<br />

<footer>

	<img src="img/barre-lapin.gif"  alt="barre" title="barre lapin"/><img>
	<br>
	<a href="#" target="_blank" title="Aller à l'accueil">Accueil</a>
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

