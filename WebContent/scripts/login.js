/**	auteur : dominique
 * 
 * hashage du mot de passe pour sécurisation
 * 
 */

function loginHash() {

	if (!randhash)
		{	alert('Formulaire corrompu. Recharger la page');
			return false; // bloquer l'envoie du formulaire en cas de mauvaise saisie
		}
	
	user 	= document.login.user.value;
	pass	= document.login.pass.value;
	sign	= hex_sha1(user + pass); // hashage du mdp qui ne doit pas circuler sur le reseau
	sign	= hex_sha1(sign + randhash); // second hashage avec la clé fournie par le serveur
	
	document.login.pass.value = sign;
	
	return true ; // envoie le formulaire
}
