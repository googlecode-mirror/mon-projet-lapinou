/****************************
 * gestion du chat via ajax *
 * Cyril THURIER
 * 4/2/2013
 ****************************/

/**********************
 * variables globales *
 **********************/
var conversations = new Array(); //hash table des conversations suivies
var dateConsultation = new Array(); //rappel des conversations precedantes
var courant; //nom de la conversation courante affichee
var MAJ = 0; //date de mise a jour;

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
		var pe = new PeriodicalExecuter(ecouter, 10);//ecoute ajax toutes les 10 sec
		var connectes = $('amis');
		connectes.onchange = function(){switch_conversation( connectes.select('option')[connectes.options.selectedIndex].innerHTML )};
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

//comparer deux conversation par nom (en vue du tri)

function compareConverse(a, b) {  
	return a.get('name') > b.get('name') ? 1 : -1;
}

// afficher une conversation donnee
function switch_conversation( nom ){
	var trouve = false;
	var iterator = 0;
	
	$A(conversations).each( function( conversation){
		//si le nom est present
		if( conversation.get('name') == nom ){
			afficher_conversation( conversation );		
			trouve = true;
		}
		//recherche dans les conversations precedentes (rappel : tri alphabetique )
		if( conversation.get('date') > dateConsultation[conversation.get('name')] 
			|| ( dateConsultation[conversation.get('name')] == undefined && conversation.get('date') != '0') ){	//nouveaux messages
			$('amis').select('option')[conversation.get('num')].style.color = "blue";//bleu
		}else {
			$('amis').select('option')[conversation.get('num')].style.color = "black";//noir
		}
	});
	//si le nom est absent, on prend le dernier
	if( trouve == false ){
		afficher_conversation( $A(conversations).last() );
	}
}
function afficher_conversation( conversation ){
	$('messages_div').update( conversation.get('div').innerHTML );//copie du contenu
	//selectionne dans la liste
	$('amis').value = conversation.get('num');
	//mise a jour de lau dernier message vu
	dateConsultation[conversation.get('name')] = conversation.get('date'); 
}



/*****************************
 * requetes ajax             *
 * recherche des messages et *
 * signale la presence       *
 *****************************/

function ecouter(){
	new Ajax.Request('chatMessages_xml.php', { //PROTOTYPE API
		onSuccess: function(response) {
			var xmlDoc = new Element('div').update(response.responseText).childElements()[0];//une div non affichee

			//derniere mise a jour
			nouvMAJ =  xmlDoc.down('derniere_MAJ').innerHTML;

			//amis connectes
			var connectes =$('amis'); //liste sur le lapiphone
			var affichage = $('messages_div');//affichage des messages
			if( connectes.options.selectedIndex > -1 ){
				courant = connectes.select('option')[connectes.options.selectedIndex].innerHTML; //conversation suivie avec...
			}else { courant = null;}

			//si une nouvelle mise a jour...
			if( MAJ < nouvMAJ ){			

				connectes.update('');//effacer la liste
				//effacer les anciennes conversations
				conversations = new Array();
			
				//nouvelle liste
				var nouvDiv = new Element('div', { 'class': 'sup', 'id':'vide'}); //une div vide
				nouvDiv.style.height = affichage.getHeight();
				affichage.insert({bottom: nouvDiv });
	
				var nAmi = 0;
				var plusRecent = null;
				var dateMessPlusRecent =0;

				//pour chaque connecte
				xmlDoc.select('ami').each( function (ami){

					//ajout dans la liste
					connectes.insert({ //inserer dans la liste
						bottom: new Element('option',{'value': nAmi }).update(ami.down('nom').innerHTML )//texte
						//value permet de forcer la selection
					});

					//conversation avec cette personne
					if( ami.down('conversation') ){

						//contient le message le plus recent ?
						var temps = ami.down('date').innerHTML;
						if( temps > dateMessPlusRecent){
							dateMessPlusRecent = temps;
							plusRecent = ami.down('nom').innerHTML;
						}
						
						//creation d'une div
						nouvDiv = new Element('div', { 'class': 'sup'}); //une div
						nouvDiv.style.height = affichage.getHeight();
						var ul = new Element('ul'); //une liste
						ami.select('message').each( function (mess){
							ul.insert({
								bottom: new Element('li').update( mess.down('texte').innerHTML ).insert({ //list item
									top: new Element('span',{'class':'id'}).update( mess.down('de').innerHTML+ " : " )
								})
							});
						});
						nouvDiv.insert({
							bottom: ul
						});
						affichage.insert({
							bottom: nouvDiv
						});

						conversations.push( new Hash({name: ami.down('nom').innerHTML, div: nouvDiv, num: nAmi, date: ami.down('date').innerHTML })); 
						//{ #nom, #div, #value, #date_consult}
						//on relie nom a la div
					}else {
						conversations.push( new Hash({name: ami.down('nom').innerHTML, div: $('vide'), num: nAmi, date: '0' }));
						//on relie le nom a la div vide
					}
					nAmi++;
			
				});
				//tri
				conversations.sort( compareConverse ); //tri alphabetique du nom
	
				//fin
				if( courant != null ){
					switch_conversation( courant ); // affiche la conversation en cours
				}else{
					switch_conversation( plusRecent );//ou le message le plus recent
				}

				if( xmlDoc.down('conversation') ){montrer_lapiphone()};//si une conversation presente, on affiche
			}
  		}
	});
}

