<?php
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

require_once "sql.php";

print_r($_SESSION);

if (!isset($_SESSION["identifiant"])) {
//non connecté : erreur
	echo "<div class='erreur'>\nLa messagerie n'est accessible qu'en étant connecté.</div>\n";
//	exit;
} else {
//connecté : afficher les messages en relation avec le membre et la fiche
$mid=$_SESSION['mid'];
/*$lid=$_POST['lid'];
echo "'$lid ".isset($lid)."'";*/
//$lid=5;
//!!! pour test : de la session

	if (!connect()) {
	//gestion de l'erreur
		echo "<div class='erreur'>\nLa messagerie n'est pas accessible actuellement.</div>\n";
		exit;
	}

	if (!isset($mid) && !isset($_SESSION['lid'])) {
	//gestion de l'erreur
	//à priori inutile avec la connexion ; conservé pour éviter un appel direct
		echo "<div class='erreur'>\nVous devez être identifié pour accéder à la messagerie.</div>\n";
		exit;
	}

	//!!! fonction js réutilisable à mettre dans un fichier séparé
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
</script>
<script type="text/javascript" language="Javascript" src="scripts/ajax.js"></script>
<script type="text/javascript" language="Javascript" src="scripts/messagerie.js"></script>


<?php
	function habille_boite($liste) {
	//dispose les entêtes de discussions dans leurs boites HTML
		$code="<div class='liste_boite'>\n<ul>\n";
		foreach ($liste as $disc) {
			$code.="<li id=\"li$disc->id_disc\">
				<div onclick=\"ouvrir_fil($disc->id_disc)\">+</div>
				<div>$disc->intitule</div>
				<div>$disc->date</div>
				<div>$disc->infos</div></li>\n";
		}
		$code.="</ul>\n</div>\n";
		return $code;
	}

//distinction des cas d'affichage
//Rq : une table supplémentaire regroupant les intervenants de la discussion 
//		et leur rôle (auteur/destinataire) aurait simplifié la condition

//$req_disc="select * from `Discussion` d join `Profil` p on d.auteur=p.id_profil where auteur='$pid' or dest='$pid' ";
//filtrer les discussions du lapin courant ou dont un lapin appartient au propriétaire courant
//priorité : le lapin courant (doit nécessairement appartenir au profil courant)

	//membre sur sa fiche : toutes ses discussions
	
	if (!isset($_SESSION['pid'])) {
	//côté membre
		if (!isset($_SESSION['lid'])) {
		//on considère que lid est fixé en affichant la fiche d'un lapin du membre et supprimé sur la fiche du membre
			$req_disc="select * from `${prefixe}Discussion` 
			d join `${prefixe}lapin` l1 on d.auteur=l1.idLap 
			join `${prefixe}lapin` l2 on d.dest=l2.idLap 
			join `${prefixe}proprietaire` p1 on p1.id_profil=l1.id_profil 
			join `${prefixe}proprietaire` p2 on p2.id_profil=l2.id_profil 
			where  p1.id_profil='$mid' or p2.id_profil='$mid' ";
		} else {
		//on considère que lid est fixé en affichant la fiche d'un lapin du membre et supprimé sur la fiche du membre
			
		//membre sur un de ses lapins : toutes les discussions du lapin

			$lid=$_SESSION['lid'];
			$req_disc="select * from `${prefixe}Discussion` 
			where auteur='$lid' or dest='$lid'";
		}
	} else {
	
	//membre sur une fiche d'un autre membre

	 	$pid=$_SESSION['pid'];
		$req_disc="select * from `${prefixe}Discussion` 
		d join `${prefixe}lapin` l1 on d.auteur=l1.idLap 
		join `${prefixe}lapin` l2 on d.dest=l2.idLap 
		join `${prefixe}proprietaire` p1 on p1.id_profil=l1.id_profil 
		join `${prefixe}proprietaire` p2 on p2.id_profil=l2.id_profil ";

		if (!isset($_SESSION['fiche'])) {
		//on considère que fiche est fixé en affichant la fiche d'un lapin d'un autre membre et supprimé sur la fiche du propriétaire

		//membre sur la fiche d'un autre membre : les seules discussions entre leurs lapins

			$req_disc.="where (p1.id_profil='$mid' and p2.id_profil='$pid')
			or (p2.id_profil='$mid' and p1.id_profil='$pid')";
		} else {

	 	//membre sur un des lapins d'un autre membre : les seules discussions des lapins du membre avec ce lapin

			$fiche=$_SESSION['fiche'];
			$req_disc.="where (p1.id_profil='$mid' and l2.idLap ='$fiche')
			or (p2.id_profil='$mid' and l1.idLap ='$fiche')";
		}
	}
	

	$liste_disc=requeteObj($req_disc);
/* echo "<pre>";
print_r($liste_disc);
echo "</pre>"; */
	if ($liste_disc!==null) {
	//affichage de la messagerie
		$code="<div class='boite'>\n";
		$code.=habille_boite($liste_disc);
		$code.="<div class='message' id='texte'></div>\n";
		$code.="</div>\n";
		echo $code;
	} else
		echo "<i>Aucun profil trouvé.</i> ".mysql_error()." ".$req_disc;

//est-ce toujours nécessaire ? La connexion est peut-être encore utile => l'ajouter systématiquement dans un document générique (moteur) ?
	disconnect();

}

//$pid=$_SESSION['pid'];
?>
