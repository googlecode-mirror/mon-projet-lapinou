///////////////////////////
// scripts necessaires //
//		   au contact    //
///////////////////////////

//gestion des erreurs
window.onerror = afficherErreur;
function afficherErreur(txtMessage, txtAdresse, noLigne){
	alert(noLigne+": "+txtAdresse+"\n"+txtMessage);
}

//afficher la region a la volee
function explicitRegion(e){
	var chaine = document.inscription.cp.value;
	//test
	var reg = new RegExp('^([0-9]{2,5})$','g');
	if( reg.test( chaine ) ){	
		var dept = chaine.substring(0,2);
		if (dept == '97'){//DOM-TOM
			var dept = chaine.substring(0,3);
			var region = departements[dept];
		}else{
			var region = departements[dept];
		}
	}	
	if( region != undefined ){
		document.inscription.region.value = region;
	}else{
		document.inscription.region.value = '';
	}
	//alert(reg);
}

//verification du formulaire
function verif(){
	alert('verif');
}

//tableau des depts
departements = {
	'01' : 'Rhone-Alpes',
	'02' : 'Picardie',
	'03' : 'Auvergne',
	'04' : 'Provence-Alpes-Cote-d\'azur',
	'05' : 'Provence-Alpes-Cote-d\'azur',
	'06' : 'Provence-Alpes-Cote-d\'azur',
	'07' : 'Rhone-Alpes',
	'08' : 'Champagne-Ardenne',
	'09' : 'Midi-Pyrenees',
	'10' : 'Champagne-Ardenne',
	'11' : 'Languedoc-Roussillon',
	'12' : 'Midi-Pyrenees',
	'13' : 'Provence-Alpes-Cote-d\'azur',
	'14' : 'Basse-Normandie',
	'15' : 'Auvergne',
	'16' : 'Poitou-Charentes',
	'17' : 'Poitou-Charentes',
	'18' : 'Centre',
	'19' : 'Limousin',
	'21' : 'Bourgogne',
	'22' : 'Bretagne',
	'23' : 'Limousin',
	'24' : 'Aquitaine',
	'25' : 'Franche-Comte',
	'26' : 'Rhone-Alpes',
	'27' : 'Haute-Normandie',
	'28' : 'Centre',
	'29' : 'Bretagne',
	'20' : 'Corse',
	'30' : 'Languedoc-Roussillon',
	'31' : 'Midi-Pyrenees',
	'32' : 'Midi-Pyrenees',
	'33' : 'Aquitaine',
	'34' : 'Languedoc-Roussillon',
	'35' : 'Bretagne',
	'36' : 'Centre',
	'37' : 'Centre',
	'38' : 'Rhone-Alpes',
	'39' : 'Franche-Comte',
	'40' : 'Aquitaine',
	'41' : 'Centre',
	'42' : 'Rhone-Alpes',
	'43' : 'Auvergne',
	'44' : 'Pays-de-la-Loire',
	'45' : 'Centre',
	'46' : 'Midi-Pyrenees',
	'47' : 'Aquitaine',
	'48' : 'Languedoc-Roussillon',
	'49' : 'Pays-de-la-Loire',
	'50' : 'Basse-Normandie',
	'51' : 'Champagne-Ardenne',
	'52' : 'Champagne-Ardenne',
	'53' : 'Pays-de-la-Loire',
	'54' : 'Lorraine',
	'55' : 'Lorraine',
	'56' : 'Bretagne',
	'57' : 'Lorraine',
	'58' : 'Bourgogne',
	'59' : 'Nord-Pas-de-Calais',
	'60' : 'Picardie',
	'61' : 'Basse-Normandie',
	'62' : 'Nord-Pas-de-Calais',
	'63' : 'Auvergne',
	'64' : 'Aquitaine',
	'65' : 'Midi-Pyrenees',
	'66' : 'Languedoc-Roussillon',
	'67' : 'Alsace',
	'68' : 'Alsace',
	'69' : 'Rhone-Alpes',
	'70' : 'Franche-Comte',
	'71' : 'Bourgogne',
	'72' : 'Pays-de-la-Loire',
	'73' : 'Rhone-Alpes',
	'74' : 'Rhone-Alpes',
	'75' : 'Ile-de-France',
	'76' : 'Haute-Normandie',
	'77' : 'Ile-de-France',
	'78' : 'Ile-de-France',
	'79' : 'Poitou-Charentes',
	'80' : 'Picardie',
	'81' : 'Midi-Pyrenees',
	'82' : 'Midi-Pyrenees',
	'83' : 'Provence-Alpes-Cote-d\'azur',
	'84' : 'Provence-Alpes-Cote-d\'azur',
	'85' : 'Pays-de-la-Loire',
	'86' : 'Poitou-Charentes',
	'87' : 'Limousin',
	'88' : 'Lorraine',
	'89' : 'Bourgogne',
	'90' : 'Franche-Comte',
	'91' : 'Ile-de-France',
	'92' : 'Ile-de-France',
	'93' : 'Ile-de-France',
	'94' : 'Ile-de-France',
	'95' : 'Ile-de-France',
	'971' : 'Guadeloupe',
	'972' : 'Martinique',
	'973' : 'Guyane',
	'974' : 'Reunion',
	'976' : 'Mayotte'
}	

/*
	function verifierPass() {
		var pass = document.getElementById("pass");
		var confpass = document.getElementById("confpass");
		if (!(pass==confpass)) {
			document.getElementById("pbpass").style.display = "block";
			document.getElementById("pbpass").style.color = "red";
			confpass.style.color = "red";
		}
	}
	
	function verifierNom() {
		var nom = document.getElementById("nom");
		if (nom.value == "") {
			document.getElementById("pbNom").style.display = "block";
			document.getElementById("pbNom").style.color = "red";
		}
	}
	
	function verifierPrenom() {
		var prenom = document.getElementById("prenom");
		if (prenom.value == "") {
			document.getElementById("pbPrenom").style.display = "block";
			document.getElementById("pbPrenom").style.color = "red";
		}
	}
*/
	


