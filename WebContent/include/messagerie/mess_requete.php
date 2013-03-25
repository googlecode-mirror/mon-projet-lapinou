<?php
//session_start();

//Rq : puisque la distinction des réponses ne se fait plus dans loadXML 
//mais par la fonction de traitement qui lui est passée, il n'est plus 
//nécessaire d'avoir des balises racines xml différentes (sauf pour l'erreur).

require_once "../sql.php";
require_once "sqlMess.php";

function xmlErreur($message) {
	header('Content-Type: application/xml');
	$data="<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<erreur>";
	$data.="<contexte>ajout messagerie</contexte>\n";
	$data.="<message>$message</message>\n";
	$data.="</erreur>";
	echo $data;
}

//!!! à prendre en compte !
$mid=$_SESSION['mid'];
/*
		if (!isset($mid) && !isset($lid)) {
	//gestion de l'erreur
	xmlErreur("Vous devez être identifié pour accéder à la messagerie.");
	exit;
}
*/

if (connect()) {
	if (!isset($_GET['corps'])) {
		if (isset($_GET['id_disc'])) {
		//!!! vérifier que la personne est bien connectée (cookie/session)
	//!!! gérer l'erreur d'absence de paramètres GET
	//!!! gérer la sécurité (ne pas inclure GET directement !)
			$req_mess=LireMessages($_GET['id_disc']);
/*			echo($req_mess);
			exit(0);*/
			//"select * from `${prefixe}Message` natural join `${prefixe}lapin` natural join `${prefixe}proprietaire` where id_disc='".$_GET['id_disc']."' order by date";
			$liste_mess=requeteObj($req_mess);

			header('Content-Type: application/xml');
			$data="<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<boite>";
			foreach ($liste_mess as $mess) {
				$data.="<message><id>$mess->id_mess</id><titre>$mess->titre</titre><nom>$mess->idLap</nom><date>$mess->date</date><proprio>$mess->nom $mess->prenom</proprio></message>\n";
			}
			$data.="\n</boite>";
			echo $data;
		} else
			if (isset($_GET['id_mess'])) {
			//!!! vérifier que la personne est bien connectée (cookie/session)
			//!!! gérer l'erreur d'absence de paramètres GET
			//!!! gérer la sécurité (ne pas inclure GET directement !)
				$req_mess=LireLeMessage($_GET['id_mess']);
				//"select texte from `${prefixe}Message` where id_mess='".$_GET['id_mess']."'";
				$corps=requete_champ_unique($req_mess);

				header('Content-Type: application/xml');
				$data="<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<message>";
				$data.="<corps>$corps</corps>";
				$data.="\n</message>";
				echo $data;
			}
	} else {
		//un message est envoyé pour ajout

		if (!isset($_GET["sujet"])) {
		//nouveau message à une discussion existante
		//contrôle des données
			$ok=true;
			if (!isset($_GET["titre"]) || !$_GET["titre"] || !isset($_GET["corps"]) || !$_GET["corps"]) {
				xmlErreur("Veuillez remplir tous les champs.");
				$ok=false;
			}
			if (!isset($_GET["id_mess"]) || !$_GET["id_mess"] || !isset($_GET["id_disc"]) || !$_GET["id_disc"]) {
				xmlErreur("Un souci a eu lieu avec le formulaire d'envoi.");
				$ok=false;
			}
			if ($ok) {
				$titre=$_GET["titre"];
				$corps=$_GET["corps"];
				$id_mess=$_GET["id_mess"];
				$id_disc=$_GET["id_disc"];

//!!! si un lapin est défini dans la session mais n'est pas celui de la discussion à laquelle on répond, cela n'est pas correct
//!!! => toujours rechercher l'identifiant et vérifier lid ou le jeter.
				if (!isset($_SESSION['lid'])) {
				//retrouver l'id du lapin qui écrit, celui du propriétaire courant
					$req_id=LireLapinDiscussion($mid,$id_disc);
					/*"SELECT id_lapin FROM `${prefixe}Discussion` d join ${prefixe}lapin l on d.dest=l.id_lapin
							WHERE id_disc='$id_disc' and l.id_profil='$mid' 
							union
							SELECT id_lapin FROM `${prefixe}Discussion` d join ${prefixe}lapin l on d.auteur=l.id_lapin
							WHERE id_disc='$id_disc' and l.id_profil='$mid'";*/
					$lid=requete_champ_unique($req_id);
					if (!isset($lid)) {
						xmlErreur("Aucun lapin n'est identifié.");
						$ok=false;
					}
				} else
					$lid=$_SESSION['lid'];
				if ($ok) {
	
					$req_ins=ecrireMessage($lid,$id_disc,$titre,$corps);
					//"insert into `${prefixe}Message` values ('', '$titre','$corps',NOW(),'$id_disc','$lid')";
					$res=requete_champ_unique($req_ins);

					header('Content-Type: application/xml');
					$data="<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<messagerie>";
					$data.="<contexte>ajout</contexte>\n";
					$data.="<retour>ok</retour>\n";
					$data.="</messagerie>";
					echo $data;
				}	
			}
		} else {
		//récupérer les données transmises
			$sujet=$_GET["sujet"];
			//$lapin=$_GET["lapin"];
			$intitule=$_GET["titre"];
			$texte=$_GET["corps"];
			$dest=$_GET["id_dest"];
			$auteur=$_GET["lid"];

			
//echo "contrôle";
		// contrôle
			$echec="";
			if ($sujet===null || trim($sujet)=="")
				$echec="Sujet vide non sélectionné !";
			if ($auteur===null || trim($auteur)=="")
				$echec="Auteur invalide ! $auteur ".implode($_GET);
			if ($dest===null || trim($dest)=="")
				$echec="Destinataire non sélectionné !";
			if ($intitule===null || trim($intitule)=="")
				$echec="Auteur invalide !";
			if ($texte===null || trim($texte)=="")
				$echec="Destinataire non sélectionné !";

			if ($echec!="")
			//erreur dans le formulaire
				xmlErreur($echec);
//!!! plutôt gérer les erreurs à la fin via le xml de la discussion ? Il ne fait que renvoyer "ok" => autre valeur pour un échec
				
			else {
			//vérification de la cohérence
//	echo "liens entre profils";
		//!!! il peut sans doute y avoir injection mysql ici !
		//!!! prévoir la création d'une discussion avec le propriétaire directement (donc $lapin="") => autre champ qui signale la page d'envoi
				$req=coherenceProprioLapin($mid,$auteur);
				//"select id_profil, id_lapin from `${prefixe}lapin` where id_profil=$mid and id_lapin='$auteur'";
				$ids=requete_par_ligne($req);
				if ($ids===null)
					$echec="Lapin et propriétaire non liés ou inconnus !".$req;

/*
echo "<pre>";
print_r($ids);
echo "</pre>";
*/
//$req="select id_profil from `${prefixe}Profil` where id_profil=$auteur";
				$req=existeLapin($auteur);
				//"select id_lapin from `${prefixe}lapin` where id_lapin=$auteur";
				$idAok=requete_champ_unique($req);
				if ($idAok===null)
					$echec="Auteur inconnu !".$req;

				$req=coherenceProprioLapin($mid,$dest);
				//"select id_profil, id_lapin from `${prefixe}lapin` where id_profil=$mid and id_lapin='$dest'";
				$autres=requete_par_ligne($req);
				if ($autres!==null)
					$echec="Le destinataire vous appartient !".$req;
	
				if ($echec!="")
				//erreur de cohérence entre les lapins et le membre
					xmlErreur($echec);
				else {
				//à partir d'ici le formulaire est correctement rempli
//echo "transaction ".mysql_client_encoding();
					$utf=preg_match("/utf/",mysql_client_encoding());
//echo "connexion : $utf<br>\n";
					if (!$utf) {
						$intitule=utf8_decode($intitule);
						$texte=utf8_decode($texte);
					}
				//ajout de la discussion
//!!! il faudrait pouvoir bloquer les transactions (+ commit) jusqu'à réception du nouvel id
//!!! attention ! pour pouvoir avoir accès à last_insert_id, la connexion doit être persistente !

/*les bouts de code suivants sont tous présentés comme permettant les transactions, mais
 * - cela provoque systématiquement une erreur dans php
 * - PMA ne tient pas compte de la transaction, malgré le paramètre persistent, et commit de suite
 * - la ligne de commande pas mieux, que ce soit begin, start transaction ou set autocommit=0 !
 * 
 * il n'est donc pas possible de définir des transactions et des requêtes de source différentes peuvent interférer entre elles ! */

/*mysql_query("SET AUTOCOMMIT=0");
	$req=" BEGIN";
	requete($req);
echo mysql_error().$req; 
	$req="insert into Discussion (sujet,intitule,auteur,dest) value ('$lapin','$intitule',$idAok,".$ids[0].");insert into Message value (null,'$intitule','$texte',LAST_INSERT_ID(),$auteur);COMMIT"; */

				//pour rechercher l'id de l'insertion il faut savoir depuis quand l'insertion s'est faite
					$req="select now()";
					$date=requete_champ_unique($req);
//	echo "$req ".mysql_error();
					$req=creerDiscussion($sujet,$intitule,$idAok,$dest);
					//"insert into `${prefixe}Discussion` (sujet,intitule,auteur,dest) value ('$sujet','$intitule',$idAok,$dest)";
//	echo "$req ";
					$res=requete($req);
					if (mysql_errno())
						$echec="$res erreur : ".mysql_error().$req;
		//puisqu'on ne peut garantir qu'il n'y a pas eu d'autre insertion entre les deux, LAST_INSERT_ID() ne peut être utilisée
		//on recherche donc la discussion créée avec ces paramètres depuis quelques secondes;
					$req=derniereDiscussion($sujet,$intitule,$idAok,$dest,$date);
					//"select id_disc from `${prefixe}Discussion` where sujet='$sujet' and intitule='$intitule' and auteur=$idAok and dest=$dest and date>='$date'";
//	echo "$req ";
					$id_d=requete_champ_unique($req);
	//Rq : il ne faut SURTOUT PAS tenter d'affecter un retour en comparant ave une valeur : $var=fonction()==null ne met pas le résultat de fonction dans var mais rien (pas même false ou true apparemment) !
					if ($id_d==null)
						$echec="erreur : ".mysql_error().$req;
//		echo $id_d;
					if ($echec!="")
					//erreur d'ajout de la nouvelle discussion
						xmlErreur($echec);
					else {
						$req=ecrireMessage($auteur,$id_d,$intitule,$texte);
						//"insert into `${prefixe}Message` value (null,'$intitule','$texte',NOW(), $id_d, $auteur)";
//	$req=mysql_real_escape_string($req);
//echo "$req ";
						if (requete($req)==null) {
						//discussion lancée
							header('Content-Type: application/xml');
							$data="<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<discussion>";
							$data.="<contexte>nouvelle</contexte>\n";
							$data.="<retour>ok</retour>\n";
							$data.="</discussion>";
							echo $data;
						} else
  							xmlErreur("erreur : ".mysql_error().$req);
  
  //echo "fin";
					}
				}
			}
		}	
	}	
} else {
//!!! à tester
	if (!isset($_GET['corps'])) {
		if (isset($_GET['id_disc'])) {
			echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<boite>\n</boite>";
		} else
			if (isset($_GET['id_mess'])) {
			//!!! à tester
				echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<boite>\n</boite>";
			}
	} else {
	//gestion de l'erreur
		xmlErreur("La messagerie n'est pas accessible actuellement.");
	}
}

?>