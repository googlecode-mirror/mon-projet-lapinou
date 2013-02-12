<?php
include "include/sql.php";

?>
<script>
function loadXMLDoc(url,no) {
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
    txt="<table border='1'><tr><th>Sujet</th><th>Date</th><th>Auteur</th></tr>";
//	alert ('retour : '+xmlhttp.responseText);
	x=xmlhttp.responseXML.documentElement.getElementsByTagName("message");
	for (i=0;i<x.length;i++) {
		txt=txt+"<tr>\n";
      xx=x[i].getElementsByTagName("titre");
      {
        try {
          txt=txt + "<td>" + xx[0].firstChild.nodeValue + "</td>";
        } catch (er) {
          txt=txt + "<td>&nbsp;</td>";
        }
      }
      xx=x[i].getElementsByTagName("date");
      {
        try {
          txt=txt + "<td>" + xx[0].firstChild.nodeValue + "</td>";
        } catch (er) {
          txt=txt + "<td>&nbsp;</td>";
        }
      }
      xx=x[i].getElementsByTagName("nom");
      {
        try {
          txt=txt + "<td>" + xx[0].firstChild.nodeValue + "</td>";
        } catch (er) {
          txt=txt + "<td>&nbsp;</td>";
        }
      }
		txt=txt+"</tr>\n";
 
    }
//	alert ('retour : '+txt);
    document.getElementById('li'+no).innerHTML=document.getElementById('li'+no).innerHTML+txt;
  }
  }
  xmlhttp.open("GET",url,true);
  xmlhttp.setRequestHeader('Content-Type',  'text/xml');
  xmlhttp.send();
}

function ouvrir_fil(no) {
	loadXMLDoc("cpt_message.php?id_disc="+no,no);
	}
</script>

<?php
function habille_boite($liste) {
	$code="<div class='liste_boite'>\n<ul>\n";
	foreach ($liste as $disc) {
		$code.="<li id=\"li".$disc['id_disc']."\"><div onclick=\"ouvrir_fil(".$disc['id_disc'].")\">+</div><div>".$disc['intitule']."</div><div>".$disc['date']."</div><div>".$disc['infos']."</div></li>\n";
	}
	$code.="</ul>\n</div>\n";
	return $code;
}

$pid=1;
//$pid=$_SESSION['pid'];
$req_disc="select * from `Discussion` d join `Profil` p on d.auteur=p.id_profil where auteur='$pid' or dest='$pid' ";
$liste_disc=requete($req_disc);
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


?>
