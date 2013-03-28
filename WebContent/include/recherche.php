<?php
/*****************************************************
 *	script effectuant la recherche dans les tables	**
 *	soit à partir :									**
 *	- d'une liste de mots-clés cherchés dans toutes	**
 *	les tables et champs pertinents ou sans risque	**
 *	- des données structurées envoyées par un		**
 *	formulaire destinées à seuls certaines tables 	**
 *	et champs										**
 * 													**
 * 		Florent Arnould	-	20 mars 2013			**
 *****************************************************/
/*
	1er cas :
		- déterminé par la présence de 'global' dans $_POST
		- attend la liste des critères comme une chaîne de 
		mots séparés par des espaces dans 'criteres' de $_POST

	2nd cas : à définir

	variables globales :
		$criteres, $types, $type

	fonctions :
		chercheCrit($genre);
		typesCriteres();
		donneChamps($table);
		nettoyerChamps($chps);
		controle_supp($req,$table);
		afficheResultat($resultats,$table);

*/

//tableau du type de chaque champ
$type=Array();

//inclure la connexion à la base et l'affichage de la fiche d'un lapin
require_once "sql.php";
include_once "affiche_lapin.inc.php";

//accès à la base
connect();

//fonctions

//		spécifiques au cas 'global'

function chercheCrit($genre) {
//global : retourne la liste des critères fournis du type $genre
//$genre est un entier dont chaque bit représente un type générique
	global $criteres, $type;

	$tab=null;
//on  parcours la liste des critères
	foreach ($type as $i => $tp) 
//on vérifie si son type est l'un de ceux attendus
		if (($tp&$genre)) 	//!!! était (($tp|$genre)!=0)
			$tab[]=$criteres[$i];
//on retourne le tableau construit
	return $tab;
}

function typesCriteres() {
//détermine la liste des critères ainsi que le type de chacun 
//et note les types disponibles parmi les critères
//tout est transmis en variables globales
	global $criteres, $types, $type;

//obtenir les critères séparés
	$criteres=split(' ',$_GET['criteres']);
//déterminer les types
	$types=0;
	foreach ($criteres as $crit) {
		if (eregi('^[0-9]+$',$crit)) {
			$types|=1;	//entier
			$type[]=1;
		}
		if (eregi('^[0-9]{4}(/|:)[0-9]{2}(/|:)[0-9]{2}$',$crit)) {
			$types|=2;	//date
			$type[]=2;
		}
		if (eregi('^[0-9]{2}:[0-9]{2}(:[0-9]{2})?$',$crit)) {
			$types|=4;	//heure
			$type[]=4;
		}
		if (eregi('[a-zA-Z]+',$crit)) {
			$types|=8;	//texte
			$type[]=8;
		}
	}	
}

function donneChamps($table) {
//retourne la liste des champs de la table fournie
	$req="SHOW FIELDS FROM $table";
	return requete($req);
}

function nettoyerChamps($chps) {
//supprime les champs à ne pas tester (identifiants, passe, 
//etc.) parmi la liste fournie. Retourne cette liste.

	//passer les champs en revue
	foreach ($chps as $i=>$chp) {
		if (isset($chp['Extra']) && $chp['Extra']=='auto_increment')
		//autoincrément = clé = identifiant
			unset ($chps[$i]);
		if (ereg("^id.*",$chp['Field']))
		//id... = identifiant
			unset ($chps[$i]);
		if (eregi("pa?s?swo?r?d",$chp['Field']))
		//mot de passe
			unset ($chps[$i]);
	}
	return $chps;
}

function controle_supp($req,$table) {
//effectue un contrôle supplémentaire sur certaines tables
//complète et retourne la requête fournie si nécessaire
	global $prefixe;

//il faut l'identifiant du membre pour le filtrage
	$mid=$_SESSION['mid'];
//contrôles
	if ($table==$prefixe."Discussion"){
//contrôle d'un participant à la discussion
		$ids_lap="IN (SELECT id_lapin FROM lapin_lapin WHERE id_profil =$mid)";
		$req.=" and (`auteur` $ids_lap OR `dest` $ids_lap)";
	}
	if ($table==$prefixe."Message") {
//contrôle d'un participant à la discussion dont le message est issu
		$ids_lap="IN (SELECT id_lapin FROM lapin_lapin WHERE id_profil =$mid)";
		$req.=" AND id_disc in (SELECT id_disc FROM ${prefixe}Discussion WHERE `auteur` $ids_lap OR `dest` $ids_lap)";
}
	return $req;
}

function afficheResultat($resultats,$table) {
//affichage générique au format html des résultats de la recherche
//globale pour cette table s'il y en a
//Rq : affiche directement, ne retourne pas de chaîne !
	global $prefixe;
	
//vérifier qu'il y a des résultats à afficher
	$nb=count($resultats);
	if ($nb!=0) {
		$s=(($nb==0)?"":"s");	//gestion du pluriel
		$chn="";	//contiendra le contenu de la table générée
	//passer les résultats en revue
		foreach ($resultats as $res) {
		//nouvelles lignes
			$entete="<tr>";		//contiendra l'entête de la table (rééccrit)
			$chn.="<tr>";
		//passer les champs en revue
			foreach ($res as $chp =>$val)
			//ne garder que les clés aphanumériques (noms) du tableau
				if (eregi("[a-z]",$chp)) {
				//ajouter cette clé à l'entête et sa valeur au contenu
					if (!((preg_match('/Message/i',$table)) && ($chp=="id_mess")))
						$entete.="<th>$chp</th>";
					if (preg_match('/Message/i',$table))
						if ($chp=="titre")
					//message : ajout d'un lien vers la messagerie
							$chn.="<td><a href='index.php?page=messagerie&disc=".$res['id_disc']."&mess=".$res['id_mess']."'>$val</a></td>";
						else
							if ($chp=="id_mess")
								;	//ne pas afficher l'identifiant
							else
								$chn.="<td>$val</td>";
					else
						$chn.="<td>$val</td>";
				}
		//fermer les lignes
			$entete.="</tr>";
			$chn.="</tr>\n";
		}
	//les résultats ont été formatés : les afficher
	//Rq : ou les retourner !
		echo "<div class='resultatRch'>
	<p>$nb résultat$s trouvé$s dans ".str_replace($prefixe,"",$table).".</p>
	<div id='rch_$table'>
		<table>
			$entete
			$chn
		</table>
	</div>
</div>\n";
	}
}

function afficheLienProprio($resultats,$table) {
//affichage générique au format html des résultats de la recherche
//globale pour cette table s'il y en a
//Rq : affiche directement, ne retourne pas de chaîne !
//vérifier qu'il y a des résultats à afficher
	$nb=count($resultats);
	if ($nb!=0) {
		$s=(($nb==0)?"":"s");	//gestion du pluriel
		$chn="";	//contiendra le contenu de la table générée
		$entete="<tr>";		//contiendra l'entête de la table (rééccrit)
		$entete.="<th>pseudo</th><th>nom</th><th>prénom</th><th>région</th><th>adresse</th>";
		$entete.="</tr>";
	//passer les résultats en revue
		foreach ($resultats as $res) {
		//nouvelles lignes
			$lien="<a href='index.php?page=profil&user=".$res['identifiant']."'>";
			$chn.="<tr>";
		//afficher les champs pertinents
			$chn.="<td>$lien".$res['identifiant']."</a></td><td>$lien".$res['nom']."</a></td><td>$lien".$res['prenom']."</a></td><td>$lien".$res['region']."</a></td><td>$lien".$res['mail']."</a></td>";
		//fermer les lignes
			$chn.="</tr>\n";
		}
	//les résultats ont été formatés : les afficher
	//Rq : ou les retourner !
		echo "<div class='resultatRch'>
	<p>$nb propriétaire$s trouvé$s.</p>
	<div id='rch_$table'>
		<table>
			$entete
			$chn
		</table>
	</div>
</div>\n";
	}
}
	
//recherche selon le type de requête
if ($_GET['type']=="global") {
//définir les types de la recherche
	typesCriteres();
	
//obtenir la liste des tables
//	$req="SHOW TABLES LIKE 'lapin_%'";
	$req="SHOW TABLES LIKE '$prefixe%'";
	$tables=requete($req);

//afficher le conteneur
	echo "<article>\n<div class='resultats'>\n";
	
//passer les tables en revue
	$nbRes=0;
	foreach ($tables as $ent) {
		if (eregi("tchat",$ent[0]))
		//éliminer les tables de chat de la recherche
			break;
//Rq : il faudrait déjà faire un premier tri des tables
//		- certaines n'ayant aucun intérêt/ne devant pas être visibles
//		- d'autant ne pouvant être accessible qu'une fois connecté		
//		mais propose un outil de recherche générique.
		
	//obtenir la liste des champs de cette table
		$chps=donneChamps($ent[0]);
		
	//sélectionner les champs compatibles avec la recherche
		$chps=nettoyerChamps($chps);
		if (count($chps)!=0) {

		//composer la requête
//!!! une façon plus efficace serait de trier les critères selon leur genre avant => fait une seule fois
			$req="SELECT * FROM ".$ent[0]." WHERE (";
			$liste="";
			foreach ($chps as $i=>$chp) {
				if ((eregi('int',$chp['Type'])) && ($types & 1)) {
				//rechercher les critères nombre
					$crt=chercheCrit(1);
					foreach ($crt as $c)
					//l'ajouter
						$req.=$chp['Field']."='".$c."' OR ";
					$liste.=$chp['Field'].", ";
				}
				if (($chp['Type']=='date') && ($types & 3)) {
				//rechercher les critères date
					$crt=chercheCrit(3);
					foreach ($crt as $c)
					//l'ajouter
						$req.=$chp['Field']."='".str_replace('/','-',$c)."' OR ";
					$liste.=$chp['Field'].", ";
				}
				if (($chp['Type']=='time') && ($types & 5)) {
				//rechercher les critères heure
					$crt=chercheCrit(5);
					foreach ($crt as $c)
					//l'ajouter
						$req.=$chp['Field']."='".$c."' OR ";
					$liste.=$chp['Field'].", ";
				}
				if (($chp['Type']=='datetime') && ($types & 7)) {
				//rechercher les critères heure ou date
					$crt=chercheCrit(7);
					foreach ($crt as $c)
					//l'ajouter
						$req.=$chp['Field']."='".str_replace('/','-',$c)."' OR ";
					$liste.=$chp['Field'].", ";
				}
				if (($chp['Type']=='timestamp') && ($types & 7)) {
				//rechercher les critères heure ou date
					$crt=chercheCrit(7);
					foreach ($crt as $c)
					//l'ajouter
						$req.="CAST(".$chp['Field']." AS DATE )='".str_replace('/','-',$c)."' OR ";
					$liste.=$chp['Field'].", ";
				}
				if ((($chp['Type']=='text') || (eregi('varchar',$chp['Type']))) && ($types & 9)) {
				//rechercher les critères texte
					$crt=chercheCrit(9);
					foreach ($crt as $c)
					//l'ajouter
						$req.=$chp['Field']." like '%".$c."%' OR ";
					$liste.=$chp['Field'].", ";
				}
			}
		//supprimer les finales
			if (preg_match("/_proprietaire/i",$ent[0]))
				$liste.="identifiant, ";
			if (preg_match("/_Message/i",$ent[0]))
				$liste.="id_mess, ";
			$liste=substr($liste,0,strlen($liste)-2);
			$req=substr($req,0,strlen($req)-3);
		//fermer la parenthèse protégeant les OR et prévenir d'une parenthèse vide
			$req.="OR false)";
		//inclure la liste des champs
			$req=str_replace("*",$liste,$req);
			if (!preg_match("/_lapin/i",$ent[0])) {
		
				if ($liste!="")
					$req=controle_supp($req,$ent[0]);
			}
		//il existe au moins un champ interrogable : requête
			if ($liste!="") {
			//effectuer la recherche
				$resultat=requete($req);
				$nbRes=$nbRes+nbResultats();
			//affichage
				if ($resultat && preg_match("/_lapin/i",$ent[0])) {
				//cas de lapins : voir leur fiche en paire
					$compteur=0;
					echo "<table>\n";
					foreach ($resultat as $lapin) {
						if ($compteur % 2 == 0 ) {
							echo "<tr><td>\n";
							affiche_lapin($lapin);
							echo "</td>";
						} else {
							echo "<td>";
							affiche_lapin($lapin);
							echo "</td></tr>\n";
						}
						$compteur++;
					}
					echo "</table>\n";
				} else
				//autre cas
					if (preg_match("/_proprietaire/i",$ent[0])) {
					//propriétaire : lien vers leur fiche
						afficheLienProprio($resultat,$ent[0]);
					} else
					//tous le reste : affichage simple
						afficheResultat($resultat,$ent[0]);
			}
		}
	}
	if ($nbRes==0)
	//rien n'a été trouvé : remplacer le contenu
		echo "<p>Aucun résultat trouvé.</p>\n";
//fermer la boite des résultats globaux
	echo "</div>\n</article>\n";
	echo "\n<script>
	completerTitre('Recherche');
	</script>\n";
	
} else {
//!!! section pour la recherche fine
}
?>