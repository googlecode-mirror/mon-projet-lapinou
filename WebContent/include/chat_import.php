<?php
/************************************
 * Mise en forme de la requete ajax *
 * de recuperation des messages     *
 ************************************/
	//entete pour le fichier envoye
	header("Content-type: text/xml");
	/*echo '<?xml version="1.0" encoding="UTF-8"?>';*/
	
	require_once("sql.php");
	connect(); //connexion MySQL

	require("chat_comm.inc.php");
	emit_signal();
	get_messages();
	
	disconnect();  //deconnexion MySQL	
?>

