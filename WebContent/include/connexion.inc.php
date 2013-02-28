<?php
	//////////////////////////////
	// Librairie de fonctions   //
	// utiles au chat           //
	// pour l'envoi de messages //
	// Cyril THURIER            //
	//////////////////////////////

	/*** ulterieurement en include config.inc.php ***/
	$hostDB = 'localhost';
	$userDB = 'root';
	$passwd = 'root';
	$dataBase = 'LAPI.NET';
	$liendb = null;
	/***************************************/
	
	/**************
	 * connection *
	 **************/
	function connect(){
		//variables globales
		global $hostDB, $userDB, $passwd, $liendb, $dataBase;
		 
		//connection SGBD
		$liendb = mysql_connect($hostDB,$userDB,$passwd);
		if( ! mysql_select_db($dataBase) ){
			return false;
		}
		return true;
	}
	/****************
	 * deconnection *
	 ****************/
	function disconnect(){
		//variables globales
		global $liendb;
		//fin connexion
		mysql_close($liendb);
	}
?>
