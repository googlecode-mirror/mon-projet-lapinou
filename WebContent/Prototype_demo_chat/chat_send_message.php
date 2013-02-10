<?php
	require("include/connexion.inc.php");
	connect(); //connexion MySQL

	require("include/chat_comm.inc.php");
	envoyer_message( $_REQUEST['dest'], $_REQUEST['texte'] );
	
	disconnect();  //deconnexion MySQL
	
?>

