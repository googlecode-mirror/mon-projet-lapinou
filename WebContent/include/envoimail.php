<?php 
$user=$_POST['user']; 
$mail=$_POST['mail']; 
$sujet=$_POST['sujet']; 
$message=$_POST['message']; 


//////ici on détermine le mail en format text 
$headers .= "Content-type: text/plain; charset=iso-8859-1\r\n"; 

////ici on détermine l'expediteur et l'adresse de réponse 
$headers .= "From: $user <$mail>\r\nReply-to : $user <$mail>\nX-Mailer:PHP"; 

$subject="$sujet"; 
$destinataire="philibertjulie@yahoo.fr"; 
$body="$message"; 
if (mail($destinataire,$subject,$body,$headers)) { 
echo "Votre mail a été envoyé<br>"; 
} else { 
echo "Une erreur s'est produite"; 
} 
?></p>
<p align="center">Vous allez bientot etre redirigé vers la page d'acceuil<br>
Si vous n'etes pas redirigé au bout de 5 secondes cliquez <a href="../index.php">ici 
</a></p>
