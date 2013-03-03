<?php
/*		script d'affichage et mise à jour du compte des 
 * 		derniers messages reçus
 * 
 * 		écrit par Florent Arnould	-	3 mars 2013
 */

session_start();
if (isset($_SESSION["identifiant"])) {
// ajout du cadre et du javascript de mise à jour
	?>
<script type="text/javascript" language="Javascript" src="scripts/ajax.js"></script>
<script>
var reponseNouveaux = function (xmlhttp,x) {
	x=donneRacine(xmlhttp,"nombre");
	try {
		var nb=x[0].firstChild.nodeValue;
  	//!!!affichage différent selon le contexte ?
	} catch (er) {
		alert(er+"Le nombre de messages reçus n'a pas pu être vérifié"+x[0]);
	}
//	alert(txt);
	var el=document.getElementById("nouveaux");
	if (nb==0)
		el.innerHTML="<p>Vous n'avez aucun nouveau message.\n</p>";
	else
		el.innerHTML="<p>Vous avez "+nb+" nouveau"+((nb>1)?"x":"")+" message"+((nb>1)?"s":"")+".\n</p>";

}

function nx_messages() {
	paramAjax ["url"]="include/nombre.php";
	loadXMLDoc(reponseNouveaux);
}

var duree=60; //en secondes
setInterval("nx_messages",duree*1000);
nx_messages();		//pour le chargement initial.
</script>
<div id='nouveaux'>
</div>

<?php
}
//non connecté : on ne fait rien
