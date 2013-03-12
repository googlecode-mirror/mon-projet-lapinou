///////////////////////////
// scripts necessaires //
//		   au contact    //
///////////////////////////

function hash(bool) {
	if (!bool) return false; // bloquer l'envoi du formulaire en cas de mauvaise saisie

	user 	= document.contact.user.value;
	mail	= document.contact.mail.value;
	sujet	= document.contact.sujet.value;
	message = document.contact.message.value;
	
	
	return true ; // envoie le formulaire
}


//gestion des erreurs
window.onerror = afficherErreur;
function afficherErreur(txtMessage, txtAdresse, noLigne){
	alert(noLigne+": "+txtAdresse+"\n"+txtMessage);
}


//verification du formulaire
function verif(){
	var alarm = document.getElementById('problemes');
	alarm.innerHTML ='';
	var bool = true;
	
	//nom user	
	var reg = new RegExp('^[0-9A-Za-z\-_]{3,}$','g');
	if( !(reg.test( document.contact.user.value ) ) ){ 
		alarm.innerHTML += "- le nom d'utilisateur n'est pas renseign&eacute;. <br/>";
		document.contact.user.focus();
		bool = false;
	}
	
	//email : ###@###.## (minimum)
	var reg = new RegExp('^[0-9A-Za-z\-_]{3,}@[0-9A-Za-z\-_]{3,}\.[A-Za-z]{2,3}$','g');
	if( !(reg.test( document.contact.mail.value ) ) ){
		alarm.innerHTML += "- e-mail incorrect.<br/>";
		document.contact.mail.focus();
		bool = false;
	}
	
	//sujet
	if( document.contact.sujet.value=="" ){ 
		alarm.innerHTML += "- le sujet n'est pas renseign&eacute;.<br/>";
		document.contact.sujet.focus();
		bool = false;
	}
	//message
	if( document.contact.message.value=="" ){ 
		alarm.innerHTML += "- le sujet n'est pas renseign&eacute;.<br/>";
		document.contact.message.focus();
		bool = false;
	}
		
	
	if(! bool) alarm.style.display = 'inline';
	return bool;
}
