//
//	script de communication http avec le serveur
//

//un tableau associatif global de paramètres est défini pour la fonction loadXMLDoc
//car cette fonction est appelée avec un nombre variable de paramètres
//et cela permet de conserver des paramètres liés à une suite de requêtes 
//(discussion > message > ajout > mise à jour) 
paramAjax = { "url":"", "disc":"", "mess":"", "dernier":false};

function donneRacine(xml,nom){
//retourne la racine XML du document xml envoyé
	return xml.responseXML.documentElement.getElementsByTagName(nom);
}

function valeurEnfantSeul(x,i,nom) {
//retourne la valeur d'un nœud unique dans une liste de nœuds
	return x[i].getElementsByTagName(nom)[0].firstChild.nodeValue;
}

//Rq : issue de messagerie.js
var reponseErreur = function (xmlhttp,x) {
//affiche une alerte en cas d'erreur
//obtenir le contexte d'envoi
	x=donneRacine(xmlhttp,"contexte");
	try {
		ctxt=x[0].firstChild.nodeValue;
//Rq : on pourrait afficher différemment selon le contexte retourné
	} catch (er) {
		ctxt="";
	}
//obtenir le message d'erreur
	x=donneRacine(xmlhttp,"message");
	try {
		txt=x[0].firstChild.nodeValue;
	} catch (er) {
		txt="";
	}
//on reste sur la même fenêtre : alerte
	alert(ctxt+"\n"+txt);
}

function loadXMLDoc(traitement) {
//gère la connexion http avec le serveur puis le traitement du document
	var xmlhttp;	//l'objet ajax
	var txt,i;		//tampon texte et indice
	var x,xx;		//racine et enfant du xml retourné

//définition de l'objet pour la requête
	if (window.XMLHttpRequest)
	// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	else
	// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

//définition de la méthode d'écoute et traitement de la réponse
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		//réponse reçue sans erreur : analyse
			x=xmlhttp.responseXML.documentElement;
		//pré-traitement
			if (x.nodeName=="erreur")
			//erreur : l'afficher
				reponseErreur(xmlhttp,x);
			else
			//données : les traiter spécifiquement
				traitement(xmlhttp,x);
		}
	}

//lancer la requête
	xmlhttp.open("GET",paramAjax["url"],true);
	xmlhttp.setRequestHeader('Content-Type',  'text/xml');
	xmlhttp.send();
}
