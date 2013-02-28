<?php
/* Notes :
	mid : identifiant du membre connecté
	lid : identifiant du lapin courant (fiche affichée) du membre connecté
	pid : identifiant du membre dont la fiche est affichée
	fiche : identifiant du lapin dont la fiche est consultée, n'appartenant pas au membre connecté
on considère que lid est fixé en affichant la fiche d'un lapin du membre et supprimé sur la fiche du membre
et de même pour fiche vis-à-vis d'un autre membre propriétaire
! il faut veiller à l'affichage de chaque fiche de définir ou supprimer ces 4 variables !
fiche / 	variable	définir				supprimer
membre						mid					lid, pid, fiche
lapin					mid, lip				pid, fiche
autre membre			mid, pid				lip, fiche
autre lapin				mid, (pid), fiche		lip
*/

require_once "sql.php";

print_r($_SESSION);

if (!isset($_SESSION["identifiant"])) {
//non connecté : erreur
	echo "<div class='erreur'>\nLa messagerie n'est accessible qu'en étant connecté.</div>\n";
//	exit;
} else {
//connecté : afficher les messages en relation avec le membre et la fiche
$mid=$_SESSION['mid'];
/*$lid=$_POST['lid'];
echo "'$lid ".isset($lid)."'";*/
//$lid=5;
//!!! pour test : de la session

	if (!connect()) {
	//gestion de l'erreur
		echo "<div class='erreur'>\nLa messagerie n'est pas accessible actuellement.</div>\n";
		exit;
	}

	if (!isset($mid) && !isset($_SESSION['lid'])) {
	//gestion de l'erreur
	//à priori inutile avec la connexion ; conservé pour éviter un appel direct
		echo "<div class='erreur'>\nVous devez être identifié pour accéder à la messagerie.</div>\n";
		exit;
	}

	//!!! fonction js réutilisable à mettre dans un fichier séparé
?>
<script>
//ajout du style structurant de la messagerie
var ref=document.createElement('link');
ref.rel="stylesheet";
ref.href="styles/messagerie.css";
ref.type="text/css";
document.getElementsByTagName("head")[0].appendChild(ref);

//ajout des fonctions javascript de la messagerie
var ref=document.createElement('script');
ref.src="scripts/ajax.js";
ref.type="text/javascript";
document.getElementsByTagName("head")[0].appendChild(ref);
</script>
<script type="text/javascript" language="Javascript" src="scripts/ajax.js"></script>
<script type="text/javascript" language="Javascript" src="scripts/messagerie.js"></script>


<?php
	function habille_boite($liste) {
	//dispose les entêtes de discussions dans leurs boites HTML
		$code="<div class='liste_boite'>\n<ul>\n";
		foreach ($liste as $disc) {
			$code.="<li id=\"li$disc->id_disc\">
				<div onclick=\"ouvrir_fil($disc->id_disc)\">+</div>
				<div>$disc->intitule</div>
				<div>$disc->date</div>
				<div>$disc->infos</div></li>\n";
		}
		$code.="</ul>\n</div>\n";
		return $code;
	}

//distinction des cas d'affichage
//Rq : une table supplémentaire regroupant les intervenants de la discussion 
//		et leur rôle (auteur/destinataire) aurait simplifié la condition

//$req_disc="select * from `Discussion` d join `Profil` p on d.auteur=p.id_profil where auteur='$pid' or dest='$pid' ";
//filtrer les discussions du lapin courant ou dont un lapin appartient au propriétaire courant
//priorité : le lapin courant (doit nécessairement appartenir au profil courant)

	//membre sur sa fiche : toutes ses discussions
	
	if (!isset($_SESSION['pid'])) {
	//côté membre
		if (!isset($_SESSION['lid'])) {
		//on considère que lid est fixé en affichant la fiche d'un lapin du membre et supprimé sur la fiche du membre
			$req_disc="select * from `${prefixe}Discussion` 
			d join `${prefixe}lapin` l1 on d.auteur=l1.idLap 
			join `${prefixe}lapin` l2 on d.dest=l2.idLap 
			join `${prefixe}proprietaire` p1 on p1.id_profil=l1.id_profil 
			join `${prefixe}proprietaire` p2 on p2.id_profil=l2.id_profil 
			where  p1.id_profil='$mid' or p2.id_profil='$mid' ";
		} else {
		//on considère que lid est fixé en affichant la fiche d'un lapin du membre et supprimé sur la fiche du membre
			
		//membre sur un de ses lapins : toutes les discussions du lapin

			$lid=$_SESSION['lid'];
			$req_disc="select * from `${prefixe}Discussion` 
			where auteur='$lid' or dest='$lid'";
		}
	} else {
	
	//membre sur une fiche d'un autre membre

	 	$pid=$_SESSION['pid'];
		$req_disc="select * from `${prefixe}Discussion` 
		d join `${prefixe}lapin` l1 on d.auteur=l1.idLap 
		join `${prefixe}lapin` l2 on d.dest=l2.idLap 
		join `${prefixe}proprietaire` p1 on p1.id_profil=l1.id_profil 
		join `${prefixe}proprietaire` p2 on p2.id_profil=l2.id_profil ";

		if (!isset($_SESSION['fiche'])) {
		//on considère que fiche est fixé en affichant la fiche d'un lapin d'un autre membre et supprimé sur la fiche du propriétaire

		//membre sur la fiche d'un autre membre : les seules discussions entre leurs lapins

			$req_disc.="where (p1.id_profil='$mid' and p2.id_profil='$pid')
			or (p2.id_profil='$mid' and p1.id_profil='$pid')";
		} else {

	 	//membre sur un des lapins d'un autre membre : les seules discussions des lapins du membre avec ce lapin

			$fiche=$_SESSION['fiche'];
			$req_disc.="where (p1.id_profil='$mid' and l2.idLap ='$fiche')
			or (p2.id_profil='$mid' and l1.idLap ='$fiche')";
		}
	}
	

	$liste_disc=requeteObj($req_disc);
/* echo "<pre>";
print_r($liste_disc);
echo "</pre>"; */
	if ($liste_disc!==null) {
	//affichage de la messagerie
		$code="<div class='boite'>\n";
		$code.=habille_boite($liste_disc);
		$code.="<div class='message' id='texte'></div>\n";
		$code.="</div>\n";
		echo $code;
	} else
		echo "<i>Aucun profil trouvé.</i> ".mysql_error()." ".$req_disc;

//est-ce toujours nécessaire ? La connexion est peut-être encore utile => l'ajouter systématiquement dans un document générique (moteur) ?
	disconnect();

}
?>
<!--  script>
cols=new Array("titre","date","nom","proprio");
function loadXMLDoc(url,no,dernier) {
	var xmlhttp;
	var txt,xx,x,i;
  if (window.XMLHttpRequest) {
  // code for IE7+, Firefox, Chrome, Opera, Safari
	xmlhttp=new XMLHttpRequest();
  } else   {
  // code for IE6, IE5
	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)   {
    //<th>Sujet</th><th>Date</th><th>Auteur</th></tr>";
/*	for (j=0;j<cols.length;j++)
	//!!!faire en sorte d'afficher l'initiale en majuscule ?
		txt+="<th>"+cols[j]+"</th>\n";
	txt+="</tr>";*/
	x=xmlhttp.responseXML.documentElement;

//	alert("Nodename: " + x.nodeName + "	"+"Nodevalue: " + x.nodeValue + " "+"Nodetype: " + x.nodeType); 
	//	alert ('retour : '+xmlhttp.responseText);
/*	if (typeof something === "undefined") 
		   alert("something is undefined");*/
		   
	if (x.nodeName=="boite") {
		x=xmlhttp.responseXML.documentElement.getElementsByTagName("message");
	    txt="<ul>";
	for (i=0;i<x.length;i++) {

/* il faudrait indiquer id_desc pour pouvoir ajouter un message à la bonne discussion*/

		noMess=x[i].getElementsByTagName("id")[0].firstChild.nodeValue;
		clic=" onclick=\"ouvrir_message("+noMess+","+(i==x.length-1)+")\"";
//		alert(x[i].getElementsByTagName("id")[0]);
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
    el=document.getElementById('li'+no);
    el.innerHTML=el.innerHTML.replace("+","-");
    el.innerHTML=el.innerHTML.replace("ouvrir_fil","plusmoins");
    el.innerHTML=el.innerHTML+txt;
	} else 
		if (x.nodeName=="message") {
//			alert("Nodename: " + x.nodeName + "	"+"Nodevalue: " + x.nodeValue + " "+"Nodetype: " + x.nodeType); 
			x=xmlhttp.responseXML.documentElement.getElementsByTagName("corps");
//		alert("Nodename: " + x.nodeName + "	"+"Nodevalue: " + x.nodeValue + " "+"Nodetype: " + x.nodeType+" parent: "+x[0].parentNode.nodeName); 
       		try {
          	txt=x[0].firstChild.nodeValue;
        	} catch (er) {
          	txt="";
        	}
        	li=document.getElementById('mess'+no);
        	disc=li.parentNode.parentNode.parentNode.getAttribute("id").substr(2,3);
//        alert(disc);
        	el=document.getElementById('message');
//        el.innerHTML=txt;
        //	if (dernier)
            	txt=txt+"<form><input type='button' name='repondre' value='Répondre' onclick=\"formReponse("+no+","+disc+")\"></form>";
        	el.innerHTML=txt;
		} else
			if (x.nodeName=="erreur") {
				x=xmlhttp.responseXML.documentElement.getElementsByTagName("contexte");
	       		try {
	              	ctxt=x[0].firstChild.nodeValue;
	              	//!!!affichage différent selon le contexte ?
	            } catch (er) {
	              	ctxt="";
	            }
				x=xmlhttp.responseXML.documentElement.getElementsByTagName("message");
	       		try {
	              	txt=x[0].firstChild.nodeValue;
	            } catch (er) {
	              	txt="";
	            }
				//!!! autre fenêtre ?
//				alert(txt);
			} else
				if (x.nodeName=="messagerie") {
					//Rq: on ne se sert pas de contexte pour l'instant
					x=xmlhttp.responseXML.documentElement.getElementsByTagName("retour");
//alert(x);
		   try {
		              	txt=x[0].firstChild.nodeValue;
		              	//!!!affichage différent selon le contexte ?
		            } catch (er) {
		              	alert(er+"Erreur ! Veuillez recommencer"+txt);
		            }
		            if (txt=="ok") {
			            //supprimer le formulaire
//			            alert(document.getElementById("message").innerHTML);
			            var motif = /<form[^]*form>/i;
						el=document.getElementById("message");
			       //     alert(el.innerHTML.replace(motif,""));
			            el.innerHTML=el.innerHTML.replace(motif,"");
			       //     document.write(el.innerHTML.replace(motif,""));
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

function ouvrir_fil(no) {
	loadXMLDoc("cpt_message.php?id_disc="+no,no,false);
	}

function ouvrir_message(no,dernier) {
	loadXMLDoc("lire_message.php?id_mess="+no,no,dernier);
	}

function plusmoins(no) {
	el=document.getElementById('li'+no);
	div1=el.getElementsByTagName("div")[0];
	ul=el.getElementsByTagName("ul");
	if (div1.innerHTML=="-") {
		ul[0].style.display="none";
		div1.innerHTML="+";
	} else {
		ul[0].style.display="block";
		div1.innerHTML="-";
	}
}

function ajout_message() {
	el=document.reponse;
	//vérification des données;
	//construction de la requête
	req="id_mess="+el.id_mess.value+"&id_disc="+el.id_disc.value+"&titre="+el.titre.value+"&corps="+el.corps.value;
//alert(req);
	loadXMLDoc("repondre.php?"+req,0,false);
	}

function formReponse(no,disc) {
    el=document.getElementById('message');
	txt="<form action='repondre.php' method='get' name='reponse' onsubmit='ajout_message();return false'>";
	//on pourrait passer id_disc ou le récupérer depuis le message précedent (id_mess);
	txt+="<input type='hidden' name='id_mess' value='"+no+"'>";
	txt+="<input type='hidden' name='id_disc' value='"+disc+"'>";
	txt+="<input type='texte' name='titre'>";
	txt+="<textarea name='corps'></textarea>";
	txt+="<input type='submit' name='submit' value='Envoyer' />\n</form>";
	motif=/<form>.*<.form>/i;
//	alert(el.innerHTML.match(/.*/i));
	el.innerHTML=el.innerHTML.replace(motif,txt);
}
</script -->

<?php 
//$pid=$_SESSION['pid'];
?>
