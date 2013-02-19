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

function loadXMLDoc() {
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
		x=xmlhttp.responseXML.documentElement;
		if (x.nodeName=="boite") {
		//réponse : liste des messages de cette discussion
			x=donneRacine(xmlhttp,"message");
			txt="<ul>";
			for (i=0;i<x.length;i++) {
				//affichage des messages
				
/* il faudrait indiquer id_desc pour pouvoir ajouter un message à la bonne discussion*/

				var noMess=valeurEnfantSeul(x,i,"id");
				var clic=" onclick=\"ouvrir_message("+noMess+","+(i==x.length-1)+")\"";
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
		} else 
			if (x.nodeName=="message") {
			//réponse : corps du message
				x=donneRacine(xmlhttp,"corps");
				try {
					txt=x[0].firstChild.nodeValue;
				} catch (er) {
					txt="";
				}
				var li=document.getElementById('mess'+paramAjax['mess']);
	//!!! cette valeur devrait venir de paramAjax
//				var disc=li.parentNode.parentNode.parentNode.getAttribute("id").substr(2,3);
				var el=document.getElementById('message');
				if (paramAjax['dernier'])
					txt=txt+"<form><input type='button' name='repondre' value='Répondre' onclick=\"formReponse("+paramAjax['mess']+","+paramAjax['disc']+")\"></form>";
				el.innerHTML=txt;		
			} else
				if (x.nodeName=="erreur") {
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
				} else
					if (x.nodeName=="messagerie") {
					//Rq: on ne se sert pas de contexte pour l'instant
						x=donneRacine(xmlhttp,"retour");
						try {
							txt=x[0].firstChild.nodeValue;
		              	//!!!affichage différent selon le contexte ?
						} catch (er) {
							alert(er+"Erreur ! Veuillez recommencer"+txt);
						}
						if (txt=="ok") {
			            //supprimer le formulaire
							var motif = /<form[^]*form>/i;
							var el=document.getElementById("message");
							el.innerHTML=el.innerHTML.replace(motif,"");
					//remettre à jour la messagerie
					//!!! récupérer le message après insertion ou relancer le clic (remettre onclic à jour et trouver no)
			        //   ouvrir_fil(no);
						}
					}
	}
  //!!! et ajouter un lien pour afficher le message puis y répondre (si le dernier)
	}
	xmlhttp.open("GET",url,true);
	xmlhttp.setRequestHeader('Content-Type',  'text/xml');
	xmlhttp.send();
}
