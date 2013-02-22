<?php	
/** auteur : dominique 
 * Formulaire de login
 * 	Variable de session : $_SESSION['identifiant']
 * 					si la variable de session existe, c'est que l'utilisateur c'est bien connecté
 * 
 * 						$_SESSION['mesLogin'] : message d'erreur login
 * 

 * 
 * Si l'utilisateur n'est pas connecté, on lui propose le formulaire de connexion
 * et sinon, le formulaire de déconnexion ou de modification du compte.
 * 		
 */
	if (isset($_POST) and isset($_POST['logout']) and isset($_SESSION['identifiant'])) {
		unset($_SESSION['identifiant']);
		$_SESSION['mesLogin'] = "Déconnexion réussie";
		
	}


	if (isset($_POST)) { 
		// tester le formulaire soumis
		if (isset($_POST['user']) and isset($_POST['pass'])) {
			// il s'agit de proposition de login
			

			// déjà connecté ? il y a un pb : on déconnecte et on envoi un message
			if (isset($_SESSION['identifiant'])) {
				unset($_SESSION['identifiant']);
				$_SESSION['mesLogin'] = "Vous étiez déjà connecté. Vous êtes maintenant déconnecté.";
				header("location: index.php");
				exit(0);
			}
			
			$_SESSION['mesLogin'] = 'con base';
			// connexion à la base de données
			//include_once('include/sql.php');
			include_once("include/connexion.inc.php");
			connect();
			
			
			// recherche de l'utilisateur user
			$rq = "SELECT passwd FROM proprietaire WHERE identifiant = '" . mysql_real_escape_string($_POST['user']) . "'";
			$result = mysql_fetch_assoc(mysql_query($rq));
			
			
			// il n'existe pas : message d'erreur
			if (!$result) {
				$_SESSION['mesLogin'] = "Nom d'utilisateur invalide".mysql_error()."|".$rq;
				header("location: index.php");
				exit(0);
			}
			
			
			// il existe : controle du code de hashage
			if (!isset($_SESSION['hash'])) { // variable de session perdue ?
				$_SESSION['mesLogin'] = "Une erreur est survenue. Recommencer";
				header("location: index.php");
				exit(0);
			}
			$cle = sha1($result['passwd'] . $_SESSION['hash']);
			
			// le pwd est faux : message d'erreur
			if ($_POST['pass'] != $cle) { // mot de passe incorrect
				$_SESSION['mesLogin'] = "Mot de passe invalide \n$cle\n".$_POST['pass']."\n".$result['passwd'];
				header("location: index.php");
				exit(0);
			}
			
			// connexion OK : variable de session initialisée
			// pour des raisons de sécurité, il est aussi préférable de réinitialiser la session
			$_SESSION['identifiant'] = $_POST['user'];
			session_regenerate_id(true);
			$_SESSION['mesLogin'] = "";
		}
	}
	

	
	

	// formulaire de connexion ou de déconnexion ?
	if (isset($_SESSION['identifiant']))
	{
		include("contenu/formLogout.php");
	}
	else 
	{
		include("contenu/formLogin.php");
	}

?>


