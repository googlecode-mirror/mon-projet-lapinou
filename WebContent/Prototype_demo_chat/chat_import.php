<?php
	//entete pour le fichier envoye
	header("Content-type: text/xml");
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	
	require("include/connexion.inc.php");
	connect(); //connexion MySQL

	require("include/chat_comm.inc.php");
	emit_signal();
	get_messages();
	
	disconnect();  //deconnexion MySQL
	
?>

