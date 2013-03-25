/****************************
 * gestion du chat via ajax *
 * Cyril THURIER            *
 * 10/2/2013                *
 ****************************/

/**********************
 * variables globales *
 **********************/

var conversations = {}; //hash table des conversations suivies
var dateConsult = {}; //rappel des conversations precedantes
var courant; //nom de la conversation courante affichee
var tri = []; // pour trier les conversations

/**********************************
 * assurer la compatibilite entre *
 * jquery et prototype            *
 **********************************/
// use jQuery as $j() and Prototype's $()
var $j = jQuery.noConflict();

/*****************
 * au chargement *
 *****************/
$j("#lapiphone").ready(
	function(){
		$j("#lapiphone").slideUp(0,null); //cacher instantanement
		//ecoute ajax toutes les 10 sec
		var pe = new PeriodicalExecuter(ecouter, 10);
		var connectes = $('amis');

		connectes.onchange = function(){
			var connectes = $('amis');
			if( connectes.select('option').length > 1 )
				switch_conversation( connectes.select('option')[connectes.options.selectedIndex].text );
		};
});


/************
 * basiques *
 ************/

// faire apparaitre le LAPIPHONE // JQUERY
function montrer_lapiphone(){
	$j("#lapiphone").slideDown();
}

// masquer le LAPIPHONE // JQUERY
function cacher_lapiphone(){
	$j("#lapiphone").slideUp();
}

// afficher une conversation donnee
function switch_conversation( nom ){
	
	var connectes =$('amis');
	connectes.update('');//effacer la liste
	
	for (var i = 0; i < tri.length; i++) { 
		
		n = tri[i];
		
		//liste
		//ajout dans la liste
		connectes.insert({ //inserer dans la liste
			bottom: new Element('option', {'value': conversations[n].get('num') }).update( n )
		});
		//recherche dans les conversations precedentes 
		if( conversations[n].get('date') > dateConsult[ n ] 
			|| ( dateConsult[ n ] == undefined && 
				conversations[n].get('date') != '0') ){	//nouveaux messages
			connectes.select('option')[conversations[n].get('num')].
				style.color = "blue";//bleu
			montrer_lapiphone();//si une conversation presente, on affiche
	
		}else {

			if( conversations[n].get('present') ){
				connectes.select('option')[conversations[n].get('num')].
				style.color = "black";//noir
			}else{
				connectes.select('option')[conversations[n].get('num')].
				style.color = "red";//rouge (absent)
			}
		}
		
		//si le nom est present
		if( n == nom ){
			afficher_conversation( conversations[n] );		
		}
	};
}
function afficher_conversation( conversation ){
	//copie du contenu
	$('messages_div').update( conversation.get('div').innerHTML );
	//selectionne dans la liste
	$('amis').value = conversation.get('num');
	//mise a jour de lau dernier message vu
	dateConsult[conversation.get('name')] = conversation.get('date'); 
}



/*****************************
 * requetes ajax             *
 * recherche des messages et *
 * signale la presence       *
 *****************************/

function ecouter(){
	new Ajax.Request('include/chat_import.php', { //PROTOTYPE API
		method: "get",
		onSuccess: function(response) {
			var xmlDoc = new Element('div').update(response.responseText).childElements()[0];


			//amis connectes
			var connectes =$('amis'); //liste sur le lapiphone
			var affichage = $('messages_div');//affichage des messages
			var vide = $('vide');//div vide
				
			//conversation suivie avec...
			if( connectes.options.selectedIndex > -1 ){
				courant = connectes.select('option')[connectes.options.selectedIndex].text; 
			}else { courant = null;}
			
			//on reinitialise les presents
			for (var c in conversations) {
				conversations[c].set('present', false );         
			}
		
			var plusRecent = null;
			var dateMessPlusRecent ='0';
			
			//pour chaque connecte
			xmlDoc.select('ami').each( function (ami){
				var valeur =null;
				//creation d'une div
				nouvDiv = new Element('div', { 'class': 'sup'}); 
				nouvDiv.style.height = affichage.getHeight();
				
				//conversation avec cette personne
				if( ami.down('conversation') ){
						//contient le message le plus recent ?
					var temps = ami.down('date').innerHTML;

					if( temps > dateMessPlusRecent){
						dateMessPlusRecent = temps;
						plusRecent = ami.down('nom').innerHTML;
					}
					

					var ul = new Element('ul'); //une liste
					ami.select('message').each( function (mess){
						ul.insert({
							bottom: new Element('li').update( mess.down('texte').innerHTML ).insert({ //list item
								top: new Element('span',{'class':'id'}).update( mess.down('de').
									innerHTML+ " : " )
							})
						});
					});
					nouvDiv.insert({
						bottom: ul
					});
					affichage.insert({
						bottom: nouvDiv
					});
					
					valeur = new Hash({name: ami.down('nom').innerHTML, div: nouvDiv, num: 0, date: ami.down('date').innerHTML, present : true });
					//{ #nom, #div, #value, #date_consult, #present?}

				}else {
					valeur = new Hash({name: ami.down('nom').innerHTML, div: nouvDiv, num: 0, date: '', present : true  });
					//on relie le nom a la div vide
				}
				conversations[ami.down('nom').innerHTML] = valeur;
				
			});

			//tri
			tri = []; //new array
			for (var c in conversations) {
				tri.push( c );        
			}

			tri.sort();
			for (var i = 0; i < tri.length; i++) {     
				conversations[tri[i]].set('num', i);         
			}
			
			//fin
			if( courant != null ){
				switch_conversation( courant ); // affiche la conversation en cours
			}else{
				switch_conversation( plusRecent );//ou le message le plus recent
			}

  		}
	});
}

/**********************
 * envoyer un message *
 **********************/
function envoyer(){
	var connectes =$('amis'); //liste sur le lapiphone
	if( connectes.options.selectedIndex > -1 ){
		courant = connectes.select('option')[connectes.options.selectedIndex].text;
		new Ajax.Request('include/chat_send_message.php', { //PROTOTYPE API
			parameters: {dest: courant , texte: $('message').value},
			method: "get",
			onSuccess: function(response) {
				$('message').value =''; //on efface
			}
		});
	}
}
