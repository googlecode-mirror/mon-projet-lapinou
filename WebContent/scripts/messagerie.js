
cols=new Array("titre","date","nom","proprio");

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
	txt=txt+"<form><input type='button' name='repondre' value='Répondre' onclick=\"formReponse("+paramAjax['mess']+","+paramAjax['disc']+")\"></form>";
	el.innerHTML=txt;
}


var reponseErreur = function () {
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
//remettre à jour la messagerie
//!!! récupérer le message après insertion ou relancer le clic (remettre onclic à jour et trouver no)
//   ouvrir_fil(no);
	}
}

function ouvrir_fil(no) {
	paramAjax ["url"]="include/mess_requete.php?id_disc="+no;
	paramAjax ["disc"]=no;
	paramAjax ["dernier"]=false;
	loadXMLDoc(reponseBoite);
}

function ouvrir_message(no) {
	paramAjax ["url"]="include/mess_requete.php?id_mess="+no;
	paramAjax ["mess"]=no;
	loadXMLDoc(reponseMessage);
	}

function ajout_message() {
	el=document.reponse;
	//vérification des données;
	//construction de la requête
	req="id_mess="+el.id_mess.value+"&id_disc="+el.id_disc.value+"&titre="+el.titre.value+"&corps="+el.corps.value;
//alert(req);
	paramAjax ["url"]="include/mess_requete.php?"+req;
	paramAjax ["disc"]=el.id_disc.value;
	loadXMLDoc(reponseMessagerie);
	}

function formReponse(no,disc) {
    el=document.getElementById('texte');
	txt="<form action='#' method='get' name='reponse' onsubmit='ajout_message();return false'>";
	//on pourrait passer id_disc ou le récupérer depuis le message précedent (id_mess);
	txt+="<input type='hidden' name='id_mess' value='"+no+"'>";
	txt+="<input type='hidden' name='id_disc' value='"+disc+"'>";
	txt+="<input type='texte' name='titre'>";
	txt+="<textarea name='corps'></textarea>";
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
