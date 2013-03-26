<?php
error_reporting(0);

include_once 'codes.php.inc';

$cnx=null;		//connexion en cours
$rep=null;		//résultat de la dernière requête

$serveur=$_SERVER["SERVER_NAME"];
$prefixe='lapin_';
if (strcasecmp($serveur,"arnould.f.free.fr")==0) {
	define('HOTE',"sql.free.fr");
	define('USER',"arnould.f");
	define('PASSE',"L4p1nG4r0u");
	define('BASE',"arnould.f");
} else 
	if (strcasecmp($serveur,"webetu")==0) {
		define('HOTE',"dbhost");
		define('USER',ETUDIANT);
		define('PASSE',ETUDIANT);
		define('BASE',"bd_".ETUDIANT);
	} else 
		if (strcasecmp($serveur,"localhost")!=0) {
			die("Pas d'information de connexion pour ce serveur !");
		}
		//les infos pour localhost se trouvent dans codes.php.inc

/**************
 * connection *
 **************/
function connect(){
//variables globales
	global $cnx, $bdd_cnx, $serveur;
	
	if ($cnx!==null)
	//déjà connecté : retourner la valeur courante
		return $cnx;
//connection SGBD
	$cnx =  mysql_pconnect(HOTE,USER,PASSE);
	if ($cnx!==null) {
		if (strcasecmp($serveur,"arnould.f.free.fr")!=0)
		//!!! à trouver !
			;
		else
			if (function_exists ("mysql_set_charset"))
				mysql_set_charset('utf8',$cnx);
			else
				mysql_query("SET NAMES 'utf8'");
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
	global $rep;
	
	$rep=mysql_query($req);
	$resultat=null;
	while ($res=mysql_fetch_array($rep))
		$resultat[]=$res;
//!!! la fonction de Cyril retournait true ou false. Or php ne considère pas null comme faux !
	return $resultat;
}		
		
function requeteObj($req) {
	//retourne un tableau d'objets contenant une ligne de résultat
	global $rep;
	
	$rep=mysql_query($req);
	$resultat=null;
	while ($res=mysql_fetch_object($rep))
		$resultat[]=$res;
	return $resultat;
}		

function requete_par_ligne($req) {
	global $rep;
	
	$rep=mysql_query($req);
	$resultat=null;
	if ($res=mysql_fetch_array($rep))
		$resultat=$res;
	return $resultat;
}		
		
function requete_par_ligneObj($req) {
	global $rep;
	
	$rep=mysql_query($req);
	$resultat=null;
	if ($res=mysql_fetch_object($rep))
		$resultat=$res;
	return $resultat;
}		
		
function requete_champ_unique($req) {
	global $rep;
	
	$rep=mysql_query($req);
	$resultat=null;
	if ($res=mysql_fetch_row($rep))
		$resultat=$res[0];
	return $resultat;
}

function nbResultats(){
	//retourne le nombre de lignes de la dernière requête effectuée.
	global $rep;
	
	if ($rep!=null)
		return mysql_num_rows($rep);
	else
		return 0;
}

	connect();
?>
