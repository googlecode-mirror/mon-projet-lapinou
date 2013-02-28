//un tableau associatif global de paramètres est défini pour la fonction loadXMLDoc
//car cette fonction est appelée avec un nombre variable de paramètres
//et cela permet de conserver des paramètres liés à une suite de requêtes 
//(discussion > message > ajout > mise à jour) 
paramAjax = { "url":"", "disc":"", "mess":"", "dernier":false};

function donneRacine(xml,nom){
	return xml.responseXML.documentElement.getElementsByTagName(nom);
}

function valeurEnfantSeul(x,i,nom) {
	return x[i].getElementsByTagName(nom)[0].firstChild.nodeValue;
}

function loadXMLDoc(traitement) {
	var xmlhttp;	//l'objet ajax
	var txt,i;		//tampon texte et indice
	var x,xx;		//racine et enfant du xml retourné

	if (window.XMLHttpRequest)
	// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	else
	// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		//réponse reçue sans erreur : analyse
//!!! tester si XML ou texte brut.
/*			if (xmlhttp.responseXML==null)
				alert (xmlhttp.responseText);*/
			x=xmlhttp.responseXML.documentElement;
			if (x.nodeName=="erreur")
				reponseErreur(xmlhttp,x);
			else
				traitement(xmlhttp,x);
		}
  //!!! et ajouter un lien pour afficher le message puis y répondre (si le dernier)
	}
	xmlhttp.open("GET",paramAjax["url"],true);
	xmlhttp.setRequestHeader('Content-Type',  'text/xml');
	xmlhttp.send();
}
