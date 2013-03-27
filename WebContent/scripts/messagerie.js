//
//	fonctions de gestion de la communication avec le
//	serveur pour obtenir les données nécessaire à
//	l'affichage et l'envoi des messages
//

//définition du message sélectionné pour la gestion de la surbrillance
var mess_select=null;
var bgcolor="";			//conserve la couleur d'origine de ce message
//nom des colonnes de la messagerie
cols=new Array("titre","date","nom","proprio");


//fonctions de surbrillance

function eteindre_message() {
//remet le message précédemment sélectionné à son état normal
	mess_select.style.backgroundColor=bgcolor;
}

function allumer_message(no) {
//souligne le message sélectionné
	mess_select=document.getElementById('mess'+no).parentNode;
	bgcolor=mess_select.style.backgroundColor;
	mess_select.style.backgroundColor="lightgray";
	}

//fonctions de traitement des réponses obtenues par ajax

var reponseBoite = function (xmlhttp,x) {
//organise les données envoyées pour les afficher sous le résumé de la discussion
//réponse : liste des messages de cette discussion
	x=donneRacine(xmlhttp,"message");
	txt="<ul>";
	for (i=0;i<x.length;i++) {
	//affichage des messages
				
//Rq :le numéro de message suffit à l'identifier ainsi que la discussion
		var noMess=valeurEnfantSeul(x,i,"id");
		var clic=" onclick=\"ouvrir_message("+noMess+")\"";
		txt=txt+"<li>\n";
		for (j=0;j<cols.length;j++) {
			xx=x[i].getElementsByTagName(cols[j]);
			try {
				txt=txt + "<div"+clic+((j==0)?" id=\"mess"+noMess+"\"":"")+">" + xx[0].firstChild.nodeValue + "</div>";
			} catch (er) {
				txt=txt;
//				txt=txt + "<div>&nbsp;</div>";
			}
		}
		txt=txt+"</li>\n"; 
	}
	txt=txt+"</ul>\n";
//ajout après l'item (li) cliqué
	//Rq : le numéro du li est celui de la discussion => 2nd identification
	var el=document.getElementById('li'+paramAjax['disc']);
//noeud ouvert : + → -
	el.innerHTML=el.innerHTML.replace("+","-");
//contenu chargé : simplement afficher/masquer maintenant
	el.innerHTML=el.innerHTML.replace("ouvrir_fil","plusmoins");
	el.innerHTML=el.innerHTML+txt;
}

var reponseMessage = function (xmlhttp,x) {
//affiche le corps du message
// x.nodeName=="message" normalement
//réponse : corps du message
	x=donneRacine(xmlhttp,"corps");
	try {
		txt=x[0].firstChild.nodeValue;
	} catch (er) {
		txt="";
	}
	var li=document.getElementById('mess'+paramAjax['mess']);
	var el=document.getElementById('texte');
	txt="<div>\n<fieldset>\n<legend>Message</legend>\n"+txt+"</fieldset>\n</div>"+"<form><input type='button' name='repondre' value='Répondre' onclick=\"formReponse("+paramAjax['mess']+","+paramAjax['disc']+")\"></form>";
	el.innerHTML=txt;
//et surligner l'entête du message
	allumer_message(paramAjax['mess']);
}

//transférer dans ajax.js
/*
var reponseErreur = function (xmlhttp,x) {
// x.nodeName=="erreur" normalement
	x=donneRacine(xmlhttp,"contexte");
	try {
		ctxt=x[0].firstChild.nodeValue;
  	//!!!affichage différent selon le contexte ?
	} catch (er) {
		ctxt="";
	}
	x=donneRacine(xmlhttp,"message");
	try {
		txt=x[0].firstChild.nodeValue;
	} catch (er) {
		txt="";
	}
//!!! autre fenêtre ?
	alert(ctxt+"\n"+txt);
}
*/

var reponseMessagerie = function (xmlhttp,x) {
//gestion de la réponse à l'envoi d'un nouveau message
// x.nodeName=="messagerie" normalement
//Rq: on ne se sert pas de contexte pour l'instant
//obtenir le contexte d'envoi
	x=donneRacine(xmlhttp,"retour");
	try {
		txt=x[0].firstChild.nodeValue;
 	} catch (er) {
		alert(er+"Erreur ! Veuillez recommencer"+txt);
	}
	if (txt=="ok") {
	//reconfigurer la messagerie
    //supprimer le formulaire
		var motif = /<form[^]*form>/i;
		var el=document.getElementById("texte");
		el.innerHTML=el.innerHTML.replace(motif,"");
	//remettre à jour la messagerie : lancer le clic depuis cette fonction
	//d'abord : vider la liste. Il n'est pas nécessaire d'inverser toutes 
	//les opérations de ouvrir_fil pour remettre dans l'état initial
		var el=document.getElementById('li'+paramAjax['disc']);
	//contenu à charger : vider le contenu actuel (liste)
		var motif = /<ul>[^]*<\/ul>/i;
		el.innerHTML=el.innerHTML.replace(motif,"");
	//recharger les messages de la discussion
		ouvrir_fil(paramAjax ["disc"]);
	}
}


var reponseDiscussion = function (xmlhttp,x) {
//gère la réponse à l'ajout d'une discussion
// x.nodeName=="discussion" normalement
//Rq: on ne se sert pas de contexte pour l'instant
//obtenir le contexte d'envoi
	x=donneRacine(xmlhttp,"retour");
	try {
		txt=x[0].firstChild.nodeValue;
	} catch (er) {
		alert(er+"Erreur ! Veuillez recommencer"+txt);
	}
	if (txt=="ok") {
    //recharger la page pour afficher les discussions
	//Rq : cela ferme toutes les branches !
//	alert("rechargement ...");
		window.location.reload();
	}
}

//fonctions de gestion des requêtes ajax

function ouvrir_fil(no) {
//gère le déploiement d'une discussion (clic sur '+')
	paramAjax ["url"]="include/messagerie/mess_requete.php?id_disc="+no;
	paramAjax ["disc"]=no;
	paramAjax ["dernier"]=false;
	loadXMLDoc(reponseBoite);
}

function ouvrir_message(no) {
//gère l'ouverture d'un message
	if (mess_select!=null)
	//effacer la surbrillance du message précédemment ouvert
		eteindre_message();
	paramAjax ["url"]="include/messagerie/mess_requete.php?id_mess="+no;
	paramAjax ["mess"]=no;
	loadXMLDoc(reponseMessage);
}

function ajout_message() {
//gère l'ajout d'un message à une discussion
	el=document.reponse;
//s'assurer que le titre ne dépasse pas les 64 caractères, malgrès l'attribut de input
	if (el.titre.value.length>64)
		alert("Le titre est trop long.");
	else {
	//vérification des données;
	//construction de la requête
		req="id_mess="+el.id_mess.value+"&id_disc="+el.id_disc.value+"&titre="+el.titre.value+"&corps="+el.corps.value;
		paramAjax ["url"]="include/messagerie/mess_requete.php?"+req;
		paramAjax ["disc"]=el.id_disc.value;
		loadXMLDoc(reponseMessagerie);
	}
}

function ajout_discussion(){
//gère la création d'une nouvelle discussion
	el=document.discussion;
//vérification des données;
//construction de la requête
	req="lid="+el.lid.value+"&sujet="+el.sujet.value+"&titre="+el.intitule.value+"&corps="+el.corps.value+"&id_dest="+el.id_dest.value;
	paramAjax ["url"]="include/messagerie/mess_requete.php?"+req;
	loadXMLDoc(reponseDiscussion);
}

function formReponse(no,disc) {
//construit et affiche le formulaire de réponse à un message ouvert
	el=document.getElementById('texte');
	txt="<form action='#' method='get' name='reponse' id='reponse' onsubmit='ajout_message();return false'>";
//on pourrait passer id_disc ou le récupérer depuis le message précedent (id_mess);
	txt+="<input type='hidden' name='id_mess' value='"+no+"'>\n";
	txt+="<input type='hidden' name='id_disc' value='"+disc+"'>\n";
	txt+="<fieldset>\n<legend>Réponse au message</legend>\n" +
			"<label>Titre du message : </label> <br \>\n<input type='texte' name='titre' maxlength='64'> <br \>\n";
	txt+="<label>Détails : </label> <br \>\n<textarea name='corps'></textarea> <br \>";
	txt+="<input type='submit' name='submit' value='Envoyer' />\n</form>";
	motif=/<form>.*<.form>/i;
	el.innerHTML=el.innerHTML.replace(motif,txt);
}

function plusmoins(no) {
//commute l'affichage des messages d'une discussion
	el=document.getElementById('li'+no);
//première division du <li> cliqué
	div1=el.getElementsByTagName("div")[0];
	ul=el.getElementsByTagName("ul");
	if (div1.innerHTML=="-") {
	//fermeture
		ul[0].style.display="none";
		div1.innerHTML="+";
	} else {
	//ouverture
		ul[0].style.display="block";
		div1.innerHTML="-";
	}
}

//section propre à l'appel depuis la recherche

function afficheRecherche() {
//fait en sorte d'afficher le message trouvé lors de la recherche
//extraire les paramètres
	param=location.search.split('&', -1);
	if (param.length>1) {
	//on vient de la recherche : ouvrir la discussion et le message sélectionnés
	//indiquer qu'il va falloir attendre un peu
		crs=document.body.style.cursor;
	//sur les div '+' aussi
	//parcourir les styles à la recherche du bon (classe pmMess)
		var i=0;
		ss=document.styleSheets[1];
		regles=ss.cssRules;
		txt="";
		while ((i<regles.length) && (typeof regles[i].selectorText=='undefined' || !regles[i].selectorText.match(".pmMess"))) {
	//!!! pour contrôle
			if (typeof regles[i].selectorText!='undefined')
				txt+=' '+regles[i].selectorText;
			i=i+1;
		}
		css=regles[i];
	//attendre ...
		crs2=css.style.cssText.match(/cursor:[^;]*;/g)[0].split(':',-1)[1];
		var re = new RegExp(crs2,"g");
		css.style.cssText=css.style.cssText.replace(re,"wait;");
		document.body.style.cursor="wait";
	//ouvertures
	//extraire le numéro de la discussion (1er paramètre pour la page)
		val=param[1].split('=', -1);
		setTimeout(function (){
			if (param[1].match("disc")) {
				ouvrir_fil(val[1]);
			}
		},1000);
	//extraire le numéro de la discussion (2nd paramètre pour la page)
		setTimeout( function(){
			if (param[2].match("mess")) {
				val=param[2].split('=', -1);
				ouvrir_message(val[1]);
			}
		//réafficher le curseur normal après l'attente de réponse du serveur pour l'oouverture de la discussion
		//Rq : n'attend pas la réception du message
		//Rq : en cas de lenteur du serveur l'ouverture ne se fait pas correctement
			document.body.style.cursor=crs;
			var re = new RegExp("wait;","g");
			css.style.cssText=css.style.cssText.replace(re,crs2);
		},2500);
	}
}

//lancer l'ouverture du message, si nécessaire
afficheRecherche();
