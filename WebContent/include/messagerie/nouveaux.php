<?php
/*****************************************************
 *	script d'affichage et mise à jour du compte des	**
 *	derniers messages reçus							**
 * 													**
 * 		Florent Arnould	-	12 mars 2013			**
 *****************************************************/

//le compte ne se fait qu'en étant connecté
if (isset($_SESSION["identifiant"])) {
// ajout du cadre et du javascript de mise à jour ; sinon rien
	?>
<script type="text/javascript" language="Javascript" src="scripts/ajax.js"></script>
<script>
//fonctions

//définition de la fonction de traitement passée à loadXMLDoc (ajax)
var reponseNouveaux = function (xmlhttp,x) {
//traite la réponse pour afficher le message convenable

//trouver la balise XML contenant le nombre
	x=donneRacine(xmlhttp,"nombre");
//lire se nombre
	try {
		var nb=x[0].firstChild.nodeValue;
	} catch (er) {
	//n'a pas été possible
		alert(er+"Le nombre de messages reçus n'a pas pu être vérifié");
	}
//trouver le conteneur
	var el=document.getElementById("nouveaux");
//afficher le résultat
	if (nb==0)
		el.innerHTML="<p>Vous n'avez aucun nouveau message.\n</p>";
	else
		el.innerHTML="<p>Vous avez "+nb+" nouveau"+((nb>1)?"x":"")+" message"+((nb>1)?"s":"")+".\n</p>";
}

function nx_messages() {
//requête ajax pour obtenir le nombre de nouveaux messages
	paramAjax ["url"]="include/messagerie/nombre.php";
	loadXMLDoc(reponseNouveaux);
}

//vérification régulière
var duree=60*1; //en secondes
setInterval("nx_messages()",duree*1000);
nx_messages();		//pour le chargement initial.
</script>
<div id='nouveaux'>
</div>

<?php
}
//non connecté : on ne fait rien
?>
