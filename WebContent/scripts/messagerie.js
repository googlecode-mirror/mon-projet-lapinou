//définition du message sélectionné pour la gestion de la surbrillance
var mess_select=null;
var bgcolor="";			//conserve la couleur d'origine de ce message

function eteindre_message() {
	mess_select.style.backgroundColor=bgcolor;
}

function allumer_message(no) {
//	alert('mess'+no);
	mess_select=document.getElementById('mess'+no).parentNode;
	bgcolor=mess_select.style.backgroundColor;
	mess_select.style.backgroundColor="lightgray";
	}

//nom des colonnes de la messagerie
cols=new Array("titre","date","nom","proprio");

//fonctions de traitement des réponses obtenues par ajax

var reponseBoite = function (xmlhttp,x) {
// x.nodeName=="boite" normalement
	//réponse : liste des messages de cette discussion
	x=donneRacine(xmlhttp,"message");
	txt="<ul>";
	for (i=0;i<x.length;i++) {
	//affichage des messages
				
/* il faudrait indiquer id_desc pour pouvoir ajouter un message à la bonne discussion*/

		var noMess=valeurEnfantSeul(x,i,"id");
		var clic=" onclick=\"ouvrir_message("+noMess+")\"";
		txt=txt+"<li>\n";
		for (j=0;j<cols.length;j++) {
			xx=x[i].getElementsByTagName(cols[j]);
			try {
				txt=txt + "<div"+clic+((j==0)?" id=\"mess"+noMess+"\"":"")+">" + xx[0].firstChild.nodeValue + "</div>";
			} catch (er) {
				txt=txt + "<div>&nbsp;</div>";
			}
		}
		txt=txt+"</li>\n"; 
	}
	txt=txt+"</ul>\n";
	//ajout après l'item (li) cliqué
	var el=document.getElementById('li'+paramAjax['disc']);
	//noeud ouvert : + → -
	el.innerHTML=el.innerHTML.replace("+","-");
	//contenu chargé : simplement afficher/masquer
	el.innerHTML=el.innerHTML.replace("ouvrir_fil","plusmoins");
	el.innerHTML=el.innerHTML+txt;
}

var reponseMessage = function (xmlhttp,x) {
// x.nodeName=="message" normalement
	//réponse : corps du message
	x=donneRacine(xmlhttp,"corps");
	try {
		txt=x[0].firstChild.nodeValue;
	} catch (er) {
		txt="";
	}
	var li=document.getElementById('mess'+paramAjax['mess']);
//!!! cette valeur devrait venir de paramAjax
//	var disc=li.parentNode.parentNode.parentNode.getAttribute("id").substr(2,3);
	var el=document.getElementById('texte');
	txt="<div>\n<fieldset>\n<legend>Message</legend>\n"+txt+"</fieldset>\n</div>"+"<form><input type='button' name='repondre' value='Répondre' onclick=\"formReponse("+paramAjax['mess']+","+paramAjax['disc']+")\"></form>";
	el.innerHTML=txt;
//	alert(paramAjax['mess']);
	allumer_message(paramAjax['mess']);
}


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


var reponseMessagerie = function (xmlhttp,x) {
// x.nodeName=="messagerie" normalement
	//Rq: on ne se sert pas de contexte pour l'instant
	x=donneRacine(xmlhttp,"retour");
	try {
		txt=x[0].firstChild.nodeValue;
  	//!!!affichage différent selon le contexte ?
	} catch (er) {
		alert(er+"Erreur ! Veuillez recommencer"+txt);
	}
//	alert(txt);
	if (txt=="ok") {
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
// x.nodeName=="discussion" normalement
	//Rq: on ne se sert pas de contexte pour l'instant
	x=donneRacine(xmlhttp,"retour");
	try {
		txt=x[0].firstChild.nodeValue;
  	//!!!affichage différent selon le contexte ?
	} catch (er) {
		alert(er+"Erreur ! Veuillez recommencer"+txt);
	}
//	alert(txt);
	if (txt=="ok") {
    //recharger la page pour afficher les discussions
	//!!! cela ferme toutes les branches !
	alert("rechargement ...");
		window.location.reload();
	}
}

//fonctions de gestion des requêtes ajax

function ouvrir_fil(no) {
	paramAjax ["url"]="include/messagerie/mess_requete.php?id_disc="+no;
//	alert("ouvrir"+paramAjax ["url"]);
	paramAjax ["disc"]=no;
	paramAjax ["dernier"]=false;
	loadXMLDoc(reponseBoite);
}

function ouvrir_message(no) {
//effacer la surbrillance du message précédemment ouvert
	if (mess_select!=null)
		eteindre_message();
	paramAjax ["url"]="include/messagerie/mess_requete.php?id_mess="+no;
	paramAjax ["mess"]=no;
	loadXMLDoc(reponseMessage);
//mettre le message en surbrillance
//	alert(no);
//	allumer_message(no);
	}

function ajout_message() {
	el=document.reponse;
	if (el.titre.value.length>64)
		alert("Le titre est trop long.");
	else {
	//vérification des données;
	//construction de la requête
	req="id_mess="+el.id_mess.value+"&id_disc="+el.id_disc.value+"&titre="+el.titre.value+"&corps="+el.corps.value;
//alert(req);
	paramAjax ["url"]="include/messagerie/mess_requete.php?"+req;
	paramAjax ["disc"]=el.id_disc.value;
	loadXMLDoc(reponseMessagerie);
	}
	}

function ajout_discussion(){
	el=document.discussion;
	//vérification des données;
	//construction de la requête
	req="lid="+el.lid.value+"&sujet="+el.sujet.value+"&titre="+el.intitule.value+"&corps="+el.corps.value+"&id_dest="+el.id_dest.value;
//alert(el+" "+req)
	paramAjax ["url"]="include/messagerie/mess_requete.php?"+req;
	loadXMLDoc(reponseDiscussion);
}

function formReponse(no,disc) {
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
//	alert(el.innerHTML.match(/.*/i));
	el.innerHTML=el.innerHTML.replace(motif,txt);
}

function plusmoins(no) {
	el=document.getElementById('li'+no);
	div1=el.getElementsByTagName("div")[0];
	ul=el.getElementsByTagName("ul");
	if (div1.innerHTML=="-") {
		ul[0].style.display="none";
		div1.innerHTML="+";
	} else {
		ul[0].style.display="block";
		div1.innerHTML="-";
	}
}

//alert(param);
function afficheRecherche() {
	param=location.search.split('&', -1);
	crs=document.body.style.cursor;
	val=param[1].split('=', -1);
	var i=0;
	ss=document.styleSheets[1];
	regles=ss.cssRules;
//	alert(regles[4].selectorText);
	txt="";
	while ((i<regles.length) && (typeof regles[i].selectorText=='undefined' || !regles[i].selectorText.match(".pmMess"))) {
		if (typeof regles[i].selectorText!='undefined')
			txt+=' '+regles[i].selectorText;
		i=i+1;
	}
	css=regles[i];
	crs2=css.style.cssText.match(/cursor:[^;]*;/g)[0].split(':',-1)[1];
	var re = new RegExp(crs2,"g");
	css.style.cssText=css.style.cssText.replace(re,"wait;");
//	alert(css.style.cssText);
//	var el=document.getElementById('li'+val[1]).firstChild.className;
//	crs2=document.styleSheets.
	document.body.style.cursor="wait";
//alert(param[0]+" "+param[1]+" "+param[2]+" ");
	setTimeout(function (){
		if (param[1].match("disc")) {
			ouvrir_fil(val[1]);
		}
	},1000);
	setTimeout( function(){
//		global crs;
		if (param[2].match("mess")) {
			val=param[2].split('=', -1);
//	alert('ùmerde');
			ouvrir_message(val[1]);
/*	  var start = new Date().getTime();
	  for (var i = 0; i < 1e7; i++) {
	    if ((new Date().getTime() - start) > 2000){
	      break;
	    }
	  }*/
//alert(val[1]);
//	setTimeout(allumer_message(val[1]),10000);
		}
		document.body.style.cursor=crs;
		var re = new RegExp("wait;","g");
		css.style.cssText=css.style.cssText.replace(re,crs2);
	},2500);
}
afficheRecherche();
//if (!location.search.match("stop"))
//	window.location.href=window.location.href+"&stop";
//setTimeout(alert("ouverture"),1000);
//setTimeout(afficheRecherche(),2000);
//setTimeout(alert("ouvert ? ..."),3000);
