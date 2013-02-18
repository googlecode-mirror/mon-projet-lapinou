<?php

include_once 'codes.php.inc';

$cnx=null;

$serveur=$_SERVER["SERVER_NAME"];
if ($serveur=="arnould.f.free.fr") {
	define('HOTE',"sql.free.fr");
	define('USER',"arnould.f");
	define('PASSE',"L4p1nG4r0u");
	define('BASE',"arnould.f");
} else 
	if ($serveur=="webetu") {
		define('HOTE',"dbhost");
		define('USER',ETUDIANT);
		define('PASSE',ETUDIANT);
		define('BASE',"bd_".ETUDIANT);
	} else 
		if ($serveur!="localhost") {
			die("Pas d'information de connexion pour ce serveur !");
		}
		//les infos pour localhost se trouvent dans codes.php.inc

/**************
 * connection *
 **************/
function connect(){
//variables globales
	global $cnx, $bdd_cnx;
	
	if (*cnx!==null)
	//déjà connecté : retourner la valeur courante
		return $cnx;
//connection SGBD
	$cnx =  mysql_pconnect(HOTE,USER,PASSE);
	if ($cnx!==null) {
		if ($serveur!="arnould.f.free.fr")
			mysql_set_charset('utf8',$cnx);
		else
		//!!! à trouver !
			;
		$bdd_cnx=mysql_select_db(BASE);
	}
	return $cnx;
}
	
/****************
 * deconnection *
 ****************/
function disconnect(){
//variables globales
	global $cnx, $bdd_cnx;

//fin connexion
	if ($cnx!==null)
	//une connexion a été établie : l'arrêter
		mysql_close($cnx);
}
	
/************
 * requêtes *
 ***********/
function requete($req) {
	$rep=mysql_query($req);
	$resultat=null;
	while ($res=mysql_fetch_array($rep))
		$resultat[]=$res;
//!!! la fonction de Cyril retournait true ou false. Or php ne considère pas null comme faux !
	return $resultat;
}		
		
function requeteObj($req) {
	//retourne un tableau d'objets contenant une ligne de résultat
	$rep=mysql_query($req);
	$resultat=null;
	while ($res=mysql_fetch_object($rep))
		$resultat[]=$res;
	return $resultat;
}		

function requete_par_ligne($req) {
	$rep=mysql_query($req);
	$resultat=null;
	if ($res=mysql_fetch_array($rep))
		$resultat=$res;
	return $resultat;
}		
		
function requete_par_ligneObj($req) {
	$rep=mysql_query($req);
	$resultat=null;
	if ($res=mysql_fetch_object($rep))
		$resultat=$res;
	return $resultat;
}		
		
function requete_champ_unique($req) {
	$rep=mysql_query($req);
	$resultat=null;
	if ($res=mysql_fetch_row($rep))
		$resultat=$res[0];
	return $resultat;
}

//echo $bdd;
				
//	echo phpinfo();

?>
