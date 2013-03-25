<?php
	//////////////////////////////
	// Librairie de fonctions   //
	// utiles au chat           //
	// pour l'envoi de messages //
	// Cyril THURIER            //
	//////////////////////////////

	/*** ulterieurement en include config.inc.php ***/
	/*
	$hostDB = 'localhost';
	$userDB = 'root';
	$passwd = 'root';
	$dataBase = 'LAPI.NET';*/
	/***************************************/

	$table_general = 'lapin_proprietaire';
	$table_conversations = 'lapin_tchat_conversation';
	$table_messages = 'lapin_tchat_message';
	
	//variables de session
	session_start();
	if( ! isset($_SESSION['identifiant']) ){ //initialise ailleurs (connexion membre)
		die("rrRAAAAAAHHHHHhhhh....");
	}
	$id = $_SESSION['identifiant'];
	if( ! isset($_SESSION['session_time']) ){
		$_SESSION['session_time'] = date('Y-m-d H:i:s');//DATETIME inialise ici !
	}
	$session_time = $_SESSION['session_time'];
	
	/**********************
	 * CLASSES            *
	 **********************/
	class Binome { //deux personnes pretes a converser, classees par ordre alphabetique
		var $nom1;
		var $nom2;
		var $session1;
		var $session2;
		
		function Binome( $nom ){//constructeur
			global $id, $session_time, $table_general;

			//nom dans l'ordre			
			if( $id < $nom ){
				$this->nom1 = $id;
				$this->session1 = $session_time;
				$this->nom2 = $nom;
				
				//recup date_acces_session
				$sql = "SELECT date_acces_session FROM ".$table_general." WHERE identifiant = '".$nom."';";
				$resultat = mysql_query($sql);
				if( $resultat ){
					$this->session2 = mysql_result($resultat,0);
				}
			}else{
				$this->nom2 = $id;
				$this->session2 = $session_time;
				$this->nom1 = $nom;	
				//recup date_acces_session
				$sql = "SELECT date_acces_session FROM ".$table_general." WHERE identifiant = '".$nom."';";
				$resultat = mysql_query($sql);
				if( $resultat ){
					$this->session1 = mysql_result($resultat,0);
				}	
			}			 
		}		 
	}
	 
	/**********************
	 * FONCTIONS          *
	 **********************/
	
	/**********************
	 * envoyer un message *
	 **********************/
	function envoyer_message( $destinataire, $texte ){
		//var globales
		global $table_conversations, $id;
		
		$binome = new Binome($destinataire);
		$conversation = null;

		//recherche de conversation courante		
		$sql = "SELECT * FROM ".$table_conversations." WHERE user1 = '".$binome->nom1
			."' AND user2 = '".$binome->nom2."' AND session_1 = '".$binome->session1
			."' AND session_2 = '".$binome->session2."';";

		$resultat = mysql_query($sql);		
		if( ! $resultat || mysql_num_rows($resultat) == 0  ){ //on cree une nouvelle conversation
			$conversation = create_conversation($destinataire);
		}else {
			$conversation = mysql_result($resultat,0,'id_conversation');
		}		
		echo "envoi";
		//nouveau message
		create_message($conversation,$destinataire,$id,$texte);
	}

	/******************************************
	 * creer un message dans une conversation *
	 * preexistante.                          *
	 * est appelle par envoyer_message        *
	 ******************************************/
	function create_message($conversation, $destinataire, $expediteur, $texte){
		echo "creer";
		//variables globales
		global $table_messages;
		 
		//date au bon format
		$moment = date('Y-m-d H:i:s');
		
		//requete	 
		$sql = "INSERT INTO ".$table_messages." (conversation, expediteur, destinataire, texte, date) ".
			"VALUES (".$conversation.",'".$expediteur."','".$destinataire."','".$texte."','".$moment."');";
			
		if ( ! mysql_query($sql) ){
			return false;
		}

		return true;
	 }

	/************************************
	 * creer une nouvelle conversation  *
	 * entre deux utilisateurs          *
	 * retourne l'id de la conversation *
	 * -1 sinon                         *
	 ************************************/	 
	function create_conversation($chater2){
				echo "conversation $chater2\n";
		//variables globales
		global $table_general, $table_messages, $table_conversations, $id, $session_time;

		$binome = new Binome($chater2);
		
		//un peu de nettoyage...
		//effacer des messages
		$sql = "DELETE FROM ".$table_messages." WHERE conversation IN ( SELECT id_conversation FROM ".$table_conversations." WHERE user1 = '".$binome->nom1."' AND user2 = '".$binome->nom2."'); ";
		//effacer les conversations precedentes concernant les noms
		if ( ! mysql_query($sql) ){
			return -1;
		}
		//effacer la conversation precedente (les noms sont dans l'ordre)
		$sql = "DELETE FROM ".$table_conversations." WHERE user1 = '".$binome->nom1."' AND user2 = '".$binome->nom2."';";
		//envoi de la requete
		if ( ! mysql_query($sql) ){
			return -1;
		}
		//un peu plus de nettoyage ?
		$aleat = rand(0,20);
		if( aleat < 2 ){
			clean_up();
		}

		//creer la conversation		
		$sql = "INSERT INTO ".$table_conversations." (user1,session_1,user2,session_2) ".
			"VALUES ('".$binome->nom1."','".$binome->session1."','".$binome->nom2."','".$binome->session2."');";
		if ( ! mysql_query($sql) ){
			echo $sql." ".mysql_error()."\n";
			return -1;
		}
		echo $sql."\n";

		return mysql_insert_id(); //retourne l'index
	}	

	/**************************************
	 * faire un peu de menage             *
	 * dans les tables :                  *
	 * effacer les messages des personnes *
	 * non connectees                     *
	 **************************************/
	function clean_up(){
		//variables globales
		global $table_general, $table_messages, $id;
		
		//delai (= maintenant - 1 minute)
		$delai = date('Y-m-d H:i:s', mktime(
			date('H'), date('i')-1, date('s'), 
			date('m'), date('d'), date('Y')));
    
		//personnes non connectees
		$sql = "SELECT identifiant FROM ".$table_general." WHERE date_dernier_signal < '".$delai."';";
		$resultat = mysql_query($sql);
		
		while( $absent = mysql_fetch_array($resultat)){
			//effacer les messages
			$sql = "DELETE FROM ".$table_messages." WHERE expediteur = '".$absent['identifiant']."' OR destinataire ='".$absent['identifiant']."';";
			mysql_query($sql);
			
			//effacer les conversations
			$sql = "DELETE FROM ".$table_conversations." WHERE user1 = '".$absent['identifiant']."' OR user2 = '".$absent['identifiant']."';";
			//envoi de la requete
			mysql_query($sql);					
		}
		return true;
	}
	
	//////////////////////////////
	// Librairie de fonctions   //
	// utiles au chat           //
	// pour la consultation     //
	// des messages             //
	// Cyril THURIER            //
	//////////////////////////////
	

	/**********************
	 * CLASSES            *
	 **********************/
	class Message {
		var $expediteur;
		var $destinataire;
		var $date;
		var $texte;
		
		function Message($exp,$dest,$date,$texte){
			$this->expediteur = $exp;
			$this->destinataire = $dest;
			$this->date = $date;
			$this->texte = $texte;
		}
	}
	function tri_date( $a, $b ){ //comparaison de messages par date
		if( $a->date > $b->date ) return 1;
		return -1;
	}
	class Conversation {
		var $date;
		var $messages; //array<Message>
		
		function Conversation(){//constructeur
			$this->messages = array();
			$this->date = 0;
		}
		function ajouterMessage( $mess ){
			array_push($this->messages, $mess);
			//mise a jour de la date
			if( $mess->date > $this->date ){
				$this->date = $mess->date;
			}
			//tri
			uasort($this->messages, "tri_date");
		}
	}	
	class Ami {
		var $nom;
		var $conversation;
		
		function Ami($nom, $conv){//constructeur
			$this->nom = $nom;
			$this->conversation =  $conv; //Conversation ou null
		}
	}
	function tri_nom( $a, $b ){ //comparaison d'amis par le nom
		if( $a->nom > $b->nom ) return 1;
		return -1;
	}
	
	class Resultat {
		var $derniere_MAJ;
		var $amis; //array<Ami>
		
		function Resultat(){//constructeur
			$this->amis = array();
			$this->derniere_MAJ ="";
		}
		
		function ajouter_ami($ami){
			array_push($this->amis,$ami);//ajout
			if( $ami->conversation != null ){
				//mise a jour
				if( $ami->conversation->date > $this->derniere_MAJ ){
					$this->derniere_MAJ = $ami->conversation->date;
				}
			}
			// tri par nom
			uasort($this->amis, "tri_nom");
		}
		
	}


	/**********************
	 * FONCTIONS          *
	 **********************/
	/******************************
	 * obtenir la liste des amis  *
	 * connectes et les messages  *
	 * des conversations en cours *
	 ******************************/
	function get_messages(){ //creation d'un Resultat
		//variables globales
		global $id, $table_general, $table_conversations, $table_messages;
		//recherche des personnes connectees
		$listing = new Resultat();
		//delai (= maintenant - 1 minute)
		$delai = date('Y-m-d H:i:s', mktime(
			date('H'), date('i')-1, date('s'), 
			date('m'), date('d'), date('Y')));
    
		//personnes connectees
		$sql = "SELECT identifiant FROM ".$table_general." WHERE date_dernier_signal > '".$delai."';";
		$resultat = mysql_query($sql);
		
		while( $present = mysql_fetch_array($resultat)){
			if( $present['identifiant'] != $id ){
				
				//creation d'un binome
				$binome = new Binome($present['identifiant']);				
				//recherche d'une conversation courante
				$sql = "SELECT * FROM ".$table_conversations." WHERE user1 = '".$binome->nom1
					."' AND user2 = '".$binome->nom2."' AND session_1 = '".$binome->session1
					."' AND session_2 = '".$binome->session2."';";
				$conversation = mysql_query($sql);
				if( ! $conversation || mysql_num_rows($conversation) == 0  ){
					//on ajoute dans la liste sans conversation
					$listing->ajouter_ami(new Ami($present['identifiant'], null));				
				}else {
					//on va chercher des messages
					$sql = "SELECT * FROM ".$table_messages." WHERE conversation = ".
						mysql_result($conversation,0,'id_conversation')." ;";
					$messages = mysql_query($sql);
					
					$maConv = new Conversation();
					while ( $message = mysql_fetch_array($messages) ){						
						//ajout de messages
						$maConv->ajouterMessage(new Message($message['expediteur'],$message['destinataire'],
							$message['date'],$message['texte']));
					}
					$listing->ajouter_ami(new Ami($present['identifiant'], $maConv));
				}					
			}
		}
		
		// parsing en XML //////////////////
		echo "\n<connectes>";
		echo "\n\t<derniere_MAJ>".$listing->derniere_MAJ."</derniere_MAJ>";
		foreach( $listing->amis as $personne ){
			echo "\n\t<ami>";
			echo "\n\t\t<nom>".$personne->nom."</nom>";
			if( $personne->conversation != null ){
				echo "\n\t\t<conversation>";
				echo "\n\t\t\t<date>".$personne->conversation->date."</date>";
				foreach( $personne->conversation->messages as $mess ){
					echo "\n\t\t\t<message>";
					echo "\n\t\t\t\t<de>".$mess->expediteur."</de>";
					echo "\n\t\t\t\t<vers>".$mess->destinataire."</vers>";
					echo "\n\t\t\t\t<texte>".$mess->texte."</texte>";					
					echo "\n\t\t\t</message>";
				}				
				echo "\n\t\t</conversation>";
			}
			echo "\n\t</ami>";
		}
		echo "\n</connectes>";		
	}

	 
	/**************************
	 * rafraichir la presence *
	 * return true si OK      *
	 * return false sinon     *
	 **************************/	 
	function emit_signal(){ //pour les tests, ne pas oublier d'inserer des noms...
		//variables globales
		global $table_general, $id, $session_time;
		 
		//date au bon format
		$moment = date('Y-m-d H:i:s');

		//requete
		$sql = "UPDATE ".$table_general." SET date_dernier_signal = '".$moment."', date_acces_session ='".
			$session_time."' WHERE identifiant = '".$id."';";

		//envoi de la requete
		if ( ! mysql_query($sql) ){
			return false;
		}
		return true;
	}	
?>
