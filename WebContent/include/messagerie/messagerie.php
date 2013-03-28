<?php
/*****************************************************
 *		script de gestion de la messagerie 			**
 *	affiche les discussions puis gère par ajax		**
 *	l'ouverture de l'arborescence des discussions	**
 *	et de leurs messages ainsi que la réponse à		**
 *	ceux-ci.										**
 * 													**
 * 		Florent Arnould	-	20 mars 2013			**
 *****************************************************/
/* Notes :
	mid : identifiant du membre connecté
	lid : identifiant du lapin courant (fiche affichée) du membre connecté
	pid : identifiant du membre dont la fiche est affichée
	fiche : identifiant du lapin dont la fiche est consultée, n'appartenant pas au membre connecté
on considère que lid est fixé en affichant la fiche d'un lapin du membre et supprimé sur la fiche du membre
et de même pour fiche vis-à-vis d'un autre membre propriétaire
! il faut veiller à l'affichage de chaque fiche de définir ou supprimer ces 4 variables !
fiche / 	variable	définir				supprimer
membre						mid					lid, pid, fiche
lapin					mid, lip				pid, fiche
autre membre			mid, pid				lip, fiche
autre lapin				mid, (pid), fiche		lip
*/

//chemin absolu du dossier include (pour court-circuiter $path et la localisation de la mesagerie dans un sous-dossier de include/)
$chm_sql= dirname($_SERVER[ 'SCRIPT_FILENAME'])."/include/";

//inclure la connexion à la base et la gestion des requêtes SQL
require_once $chm_sql."sql.php";
require_once "sqlMess.php";

//mettre le titre de la page à jour
echo "<script>
	completerTitre('Messagerie');
	</script>\n";

//traitement 

if (!isset($_SESSION["identifiant"])) {
//non connecté : erreur
	echo "<div class='erreur'>\nLa messagerie n'est accessible qu'en étant connecté.</div>\n";
} else {
//connecté : afficher les messages en relation avec le membre et la fiche

//identifier le membre connecté
	$mid=$_SESSION['mid'];

//connecté à la base ?
	if (!connect()) {
	//gestion de l'erreur
		echo "<div class='erreur'>\nLa messagerie n'est pas accessible actuellement.</div>\n";
		exit;
	}

//aucun membre identifié (ou un des ses lapins)
	if (!isset($mid) && !isset($_SESSION['lid'])) {
	//gestion de l'erreur
	//Rq : à priori inutile avec la connexion ; conservé pour éviter un appel direct
		echo "<div class='erreur'>\nVous devez être identifié pour accéder à la messagerie.</div>\n";
		exit;
	}

//!!! fonctions js réutilisables à mettre dans un fichier séparé
?>
<script>
//ajout du style structurant de la messagerie
var ref=document.createElement('link');
ref.rel="stylesheet";
ref.href="styles/messagerie.css";
ref.type="text/css";
document.getElementsByTagName("head")[0].appendChild(ref);

//ajout des fonctions javascript de la messagerie
var ref=document.createElement('script');
ref.src="scripts/ajax.js";
ref.type="text/javascript";
document.getElementsByTagName("head")[0].appendChild(ref);
//ajout de la fonctionnalité de communication
</script>
<script type="text/javascript" language="Javascript" src="scripts/ajax.js"></script>

<?php
//fonctions

	function habille_boite($liste) {
	//dispose les entêtes de discussions dans leurs boites HTML
		$code="<div class='liste_boite'>\n<ul>\n";
		foreach ($liste as $disc) {
			$code.="<li id=\"li$disc->id_disc\">
				<div class='pmMess' onclick=\"ouvrir_fil($disc->id_disc)\">+</div>
				<div>$disc->intitule</div>
				<div>$disc->date</div>
				<div>$disc->nomlap</div></li>\n";
//				<div>$disc->nom $disc->prenom</div></li>\n";
		}
		$code.="</ul>\n</div>\n";
		return $code;
	}

//distinction des cas d'affichage
//Rq : une table supplémentaire regroupant les intervenants de la discussion 
//		et leur rôle (auteur/destinataire) aurait simplifié la condition

//filtrer les discussions du lapin courant ou dont un lapin appartient au propriétaire courant
//priorité : le lapin courant (doit nécessairement appartenir au profil courant)

/*Rq : ces considérations et leur implémentation sont relatives à
 *		la disposition initiale qui prévoyait d'inclure la messagerie
 *		dans la page de profil d'un propriétaire voire d'un lapin.
 *		Il a été finalement décidé qu'elle serait affichée indépendamment.
 *		Le code est néanmoins maintenu pour l'instant en vue d'un
 *		possible retour à la disposition d'origine. */

//construite la requête
	
	if (!isset($_SESSION['pid'])) {
	//côté membre
		if (!isset($_SESSION['lid'])) {
		//on considère que lid est fixé en affichant la fiche d'un lapin du membre et supprimé sur la fiche du membre

		//membre sur sa fiche : toutes ses discussions

			$req_disc=DiscProprio($mid);
		} else {
		//on considère que lid est fixé en affichant la fiche d'un lapin du membre et supprimé sur la fiche du membre
			
		//membre sur un de ses lapins : toutes les discussions du lapin

			$lid=$_SESSION['lid'];
			$req_disc=DiscLapin($lid);
		}
	} else {
	//côté autre membre	
	//membre sur une fiche d'un autre membre

	 	$pid=$_SESSION['pid'];

		if (!isset($_SESSION['fiche'])) {
		//on considère que fiche est fixé en affichant la fiche d'un lapin d'un autre membre et supprimé sur la fiche du propriétaire

		//membre sur la fiche d'un autre membre : les seules discussions entre leurs lapins

			$req_disc=DiscAutreProprio ($mid,$pid);
		} else {

	 	//membre sur un des lapins d'un autre membre : les seules discussions des lapins du membre avec ce lapin

			$fiche=$_SESSION['fiche'];
			$req_disc=DiscAutreLapin ($mid,$fiche);
		}
	}

//effectuer la requête
	$liste_disc=requeteObj($req_disc);
	if ($liste_disc!==null) {
	//affichage de la messagerie
		$code="<article>\n<h2>Messagerie de ".$_SESSION[identifiant]."</h2>\n<div class='boite'>\n";
		$code.=habille_boite($liste_disc);
		$code.="<div class='message' id='texte'></div>\n";
	
	//affichage du formulaire de nouvelle discussion
		if (isset($_SESSION['fiche'])) {
		//lapins du membre
			$req_lapin=IdNomLapin($mid);
			$lapins=requeteObj($req_lapin);
		//normalement il devrait toujours y avoir au moins un lapin
		//mais s'il n'y en a pas (mort du dernier) ne rien afficher
			if ($lapins!==null) {
				$code.="<div id='nx_discussion'>";
				$code.="<form action='#' method='get' name='discussion' onsubmit='ajout_discussion();return false;'>\n";
				$code.="<fieldset>\n<label>Auteur : </label><select name='lid'>";
				foreach ($lapins as $lapin)
					$code.="<option value='$lapin->id_lapin'>$lapin->nomlap</option>";
				$code.="</select>";
				$code.="<label>Thème : </label><input type='text' name='sujet' value=''>";
				$code.="<br />Détails<br /><div class='detailsMess'>\n";
				$code.="<label>Titre : </label><input type='text' name='intitule' value=''>";
				$code.="<label>Message : </label><textarea name='corps'></textarea>\n</div>";
			//mid est passé par la session
				$code.="<input type='hidden' name='id_dest' value='".$_SESSION['fiche']."'>";
				$code.="<input type='submit' name='submit' value='Envoyer' />\n</form>";
				$code.="</fieldset>\n</div>";
			}
		}
	//clore les conteneurs
		$code.="</div>\n</article>\n";
		echo $code;

	//mettre à jour l'heure de dernière consultation pour le compte des nouveaux messages
		$req_date=MajConsultation ($mid);
		$lapins=requete_champ_unique($req_date);
	} else
		echo "<i>Aucune discussion trouvée.</i> ";

//est-ce toujours nécessaire ? La connexion est peut-être encore utile => l'ajouter systématiquement dans un document générique (moteur) ?
	disconnect();

//ne pas oublier les fonctionnalités de communications avec le serveur pour ouvrir les discussions et les messages.
?>
	<script type="text/javascript" language="Javascript" src="scripts/messagerie.js"></script>
<?php 
}

//$pid=$_SESSION['pid'];
?>
