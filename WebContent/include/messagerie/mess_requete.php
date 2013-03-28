<?php
/*****************************************************
 *	Gère les demandes de données de la messagerie	**
 *	pour afficher les messages, le corps de message	**
 *	le formulaire de réponse et enregistrer cette	**
 *	réponse.										**
 *	Les données sont retournées en XML				**
 * 													**
 * 		Florent Arnould	-	26 mars 2013			**
 *****************************************************/

//Rq : puisque la distinction des réponses ne se fait plus dans loadXML 
//mais par la fonction de traitement qui lui est passée, il n'est plus 
//nécessaire d'avoir des balises racines xml différentes (sauf pour l'erreur).

session_start();

//inclure la connexion à la base et la gestion des requêtes SQL
require_once "../sql.php";
require_once "sqlMess.php";

//fonctions

function xmlErreur($message) {
//envoi un message d'erreur à la messagerie au format XML.
	header('Content-Type: application/xml');
	$data="<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<erreur>";
	$data.="<contexte>ajout messagerie</contexte>\n";
	$data.="<message>$message</message>\n";
	$data.="</erreur>";
	echo $data;
}

//identifier le membre connecté
$mid=$_SESSION['mid'];

//traitement

if (connect()) {
//connecté à la base
	if (!isset($_GET['corps'])) {
	//ouverture d'une discussion ou d'un message
		if (isset($_GET['id_disc'])) {
		//développer la discussion
//!!! vérifier que la personne est bien connectée (cookie/session)
//!!! gérer l'erreur d'absence de paramètres GET
//!!! gérer la sécurité (ne pas inclure GET directement !)

		//obtenir les messages de cette discussion
			$req_mess=LireMessages($_GET['id_disc']);
			$liste_mess=requeteObj($req_mess);

		//envoyer le résultat au format XML
			header('Content-Type: application/xml');
			$data="<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<boite>";
			foreach ($liste_mess as $mess) {
				$data.="<message><id>$mess->id_mess</id><titre>$mess->titre</titre><nom>$mess->idLap</nom><date>$mess->date</date><proprio>$mess->nomlap</proprio></message>\n";
//				$data.="<message><id>$mess->id_mess</id><titre>$mess->titre</titre><nom>$mess->idLap</nom><date>$mess->date</date><proprio>$mess->nom $mess->prenom</proprio></message>\n";
			}
			$data.="\n</boite>";
			echo $data;
		} else
			if (isset($_GET['id_mess'])) {
			//ouvrir le message
//!!! vérifier que la personne est bien connectée (cookie/session)
//!!! gérer l'erreur d'absence de paramètres GET
//!!! gérer la sécurité (ne pas inclure GET directement !)

			//obtenir le corps du message
				$req_mess=LireLeMessage($_GET['id_mess']);
				$corps=str_replace("\n","<br />",requete_champ_unique($req_mess));

			//envoyer le résultat au format XML
				header('Content-Type: application/xml');
				$data="<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<message>";
				$data.="<corps>$corps</corps>";
				$data.="\n</message>";
				echo $data;
			} /*else {
			//suppression
			//en cours de développement
				$nos=str_replace('cb','',$_GET['liste']);
				$nos=str_replace(' ',',',$nos);
				$req="select * from ${prefixe} where id_mess in (select $nos)";
				$tab=requete($req);
				$txt=implode(':',$tab);
				header('Content-Type: application/xml');
				$data="<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<suppression>";
				$data.="<nombre>$txt</nombre>";
				$data.="\n</suppression>";
				echo $data;
			}*/
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
			//pas d'erreur : on continue
				$titre=$_GET["titre"];
				$corps=$_GET["corps"];
				$id_mess=$_GET["id_mess"];
				$id_disc=$_GET["id_disc"];

				if (!isset($_SESSION['lid'])) {
				//retrouver l'id du lapin qui écrit, celui du propriétaire courant
					$req_id=LireLapinDiscussion($mid,$id_disc);
					$lid=requete_champ_unique($req_id);
					if (!isset($lid)) {
						xmlErreur("Aucun lapin n'est identifié.");
						$ok=false;
					}
				} else
				//hyp : le lapin défini dans la session est celui de la discussion à laquelle on répond.
					$lid=$_SESSION['lid'];

			//un lapin a-t-il bien été défini pour le membre ?
				if ($ok) {
				//on peut enregistrer le nouveau message
					$req_ins=ecrireMessage($lid,$id_disc,$titre,$corps);
					$res=requete_champ_unique($req_ins);

				//envoyer le résultat au format XML
					header('Content-Type: application/xml');
					$data="<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<messagerie>";
					$data.="<contexte>ajout</contexte>\n";
					$data.="<retour>ok</retour>\n";
					$data.="</messagerie>";
					echo $data;
				}	
			}
		} else {
		//nouvelle discussion
		//récupérer les données transmises
			$sujet=$_GET["sujet"];
			$intitule=$_GET["titre"];
			$texte=$_GET["corps"];
			$dest=$_GET["id_dest"];
			$auteur=$_GET["lid"];

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
//!!! il peut sans doute y avoir injection mysql ici !
			//Rq : la création d'une discussion avec le propriétaire directement n'est pas permis
			//auteur = lapin du membre ?
				$req=coherenceProprioLapin($mid,$auteur);
				$ids=requete_par_ligne($req);
				if ($ids===null)
					$echec="Lapin et propriétaire non liés ou inconnus !".$req;

			//ce lapin existe-il ? 
			//Rq : à priori oui si le test précédent est passé ; mais la gestion d'erreur est faite à la fin des tests de cohérence
				$req=existeLapin($auteur);
				$idAok=requete_champ_unique($req);
				if ($idAok===null)
					$echec="Auteur inconnu !".$req;

			//destinataire = lapin du membre ?
				$req=coherenceProprioLapin($mid,$dest);
				$autres=requete_par_ligne($req);
				if ($autres!==null)
					$echec="Le destinataire vous appartient !".$req;
	
				if ($echec!="")
				//erreur de cohérence entre les lapins et le membre
					xmlErreur($echec);
				else {
				//à partir d'ici le formulaire est correctement rempli
				//s'assurer que le connexxion est en utf-8 pour les caractères accentués.
					$utf=preg_match("/utf/",mysql_client_encoding());
					if (!$utf) {
						$intitule=utf8_decode($intitule);
						$texte=utf8_decode($texte);
					}
				//ajout de la discussion
//Rq : il faudrait pouvoir bloquer les transactions (+ commit) jusqu'à réception du nouvel id ; impossible sous MySQL | free
//Rq : pour avoir accès à last_insert_id, la connexion doit être persistente !

/*les bouts de code suivants sont tous présentés comme permettant les transactions, mais
 * - cela provoque systématiquement une erreur dans php
 * - PMA ne tient pas compte de la transaction, malgré le paramètre persistent, et commit de suite
 * - la ligne de commande pas mieux, que ce soit begin, start transaction ou set autocommit=0 !
 * 
 * il n'est donc pas possible de définir des transactions et des requêtes de source différentes peuvent interférer entre elles !
 *
 * mysql_query("SET AUTOCOMMIT=0");
 * 	$req=" BEGIN";
 *  */

				//pour rechercher l'id de l'insertion il faut savoir depuis quand l'insertion s'est faite
					$req="select now()";
					$date=requete_champ_unique($req);
					$req=creerDiscussion($sujet,$intitule,$idAok,$dest);
					$res=requete($req);
					if (mysql_errno())
						$echec="erreur lors de la création de la discussion.";
				//puisqu'on ne peut garantir qu'il n'y a pas eu d'autre insertion entre les deux (pb LAST_INSERT_ID())
				//on recherche donc la discussion créée avec ces paramètres depuis quelques secondes.
					$req=derniereDiscussion($sujet,$intitule,$idAok,$dest,$date);
					$id_d=requete_champ_unique($req);
				//Rq : il ne faut SURTOUT PAS tenter d'affecter un retour en comparant avec une valeur : $var=fonction()==null ne met pas le résultat de fonction dans var mais rien (pas même false ou true apparemment) !
					if ($id_d==null || trim($id_d)=="")
						$echec="erreur : discussion introuvable. Sans doute non créée.";
					if ($echec!="")
					//erreur d'ajout de la nouvelle discussion
						xmlErreur($echec);
					else {
					//discussion lancée
					//on peut maintenant enregistrer le nouveau message
						$req=ecrireMessage($auteur,$id_d,$intitule,$texte);
						requete($req);
						if (mysql_errno()==0) {
//						if (requete($req)==null) {
							header('Content-Type: application/xml');
							$data="<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<discussion>";
							$data.="<contexte>nouvelle</contexte>\n";
							$data.="<retour>ok</retour>\n";
							$data.="</discussion>";
							echo $data;
						} else
  							xmlErreur("erreur lors de la création du message d'ouverture.");
 					}
				}
			}
		}	
	}	
} else {
//pas de connexion sql : renvoyer des contenants vides pour la cohérence du code html
	if (!isset($_GET['corps'])) {
		if (isset($_GET['id_disc'])) {
			echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<boite>\n</boite>";
		} else
			if (isset($_GET['id_mess'])) {
				echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?> \n<boite>\n</boite>";
			}
	} else {
	//gestion de l'erreur
		xmlErreur("La messagerie n'est pas accessible actuellement.");
	}
}

?>