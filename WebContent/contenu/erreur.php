<h3>Une erreur s'est produite.</h3>
<p><?php if (isset($_GET['message']))  echo $_GET['message'] ; ?></p>
<br />
<script>setTimeout( function(){ window.location.replace("index.php"); } ,5000);</script>
<p align="center">Vous allez bientot etre redirigé vers la page d'acceuil<br>
Si vous n'etes pas redirigé au bout de 5 secondes cliquez <a href="index.php">ici 
</a></p>
