<?php
/************************************
 * Mise en forme de la requete ajax *
 * d'envoi des messages             *
 ************************************/
	require_once("sql.php");
	connect(); //connexion MySQL

	require("chat_comm.inc.php");
	envoyer_message( $_REQUEST['dest'], $_REQUEST['texte'] );
	
	disconnect();  //deconnexion MySQL
	
?>

