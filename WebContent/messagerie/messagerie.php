<?php
require_once "include/sql.php";

/*$lid=$_POST['lid'];
echo "'$lid ".isset($lid)."'";*/
//$lid=5;
//!!! pour test : de la session
$pid=1;

if (!connect()) {
	//gestion de l'erreur
	echo "<div class='erreur'>\nLa messagerie n'est pas accessible actuellement.</div>\n";
	exit;
}

if (!isset($pid) && !isset($lid)) {
	//gestion de l'erreur
	echo "<div class='erreur'>\nVous devez être identifié pour accéder à la messagerie.</div>\n";
	exit;
}
//!!! fonction js réutilisable à mettre dans un fichier séparé
?>
<html><head><link rel="stylesheet" href="style/messagerie.css" type="text/css" /></head></html>
<script>
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
        	if (dernier)
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
</script>

<?php
function habille_boite($liste) {
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
//$pid=$_SESSION['pid'];
//$req_disc="select * from `Discussion` d join `Profil` p on d.auteur=p.id_profil where auteur='$pid' or dest='$pid' ";
//filtrer les discussions du lapin courant ou dont un lapin appartient au propriétaire courant
//priorité : le lapin courant (doit nécessairement appartenir au profil courant)
if (isset($lid)) {
	$req_disc="select * from `Discussion` 
	where auteur='$lid' or dest='$lid'";
} else {
	$req_disc="select * from `Discussion` 
	d join `Lapin` l1 on d.auteur=l1.id_lapin 
	join `Lapin` l2 on d.dest=l2.id_lapin 
	join `Profil` p1 on p1.id_profil=l1.id_profil 
	join `Profil` p2 on p2.id_profil=l2.id_profil 
	where  p1.id_profil='$pid' or p2.id_profil='$pid' ";
}
	$liste_disc=requeteObj($req_disc);
/* echo "<pre>";
print_r($liste_disc);
echo "</pre>"; */
if ($liste_disc!==null) {
	//affichage de la messagerie
	$code="<div class='boite'>\n";
	$code.=habille_boite($liste_disc);
	$code.="<div class='message' id='message'></div>\n";
	$code.="</div>\n";
	echo $code;
} else
echo "<i>Aucun profil trouvé.</i> ".mysql_error()." ".$req_disc;

//est-ce toujours nécessaire ? La connexion est peut-être encore utile => l'ajouter systématiquement dans un document générique (moteur) ?
disconnect();
?>
