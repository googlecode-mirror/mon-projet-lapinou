<?php
//echo phpinfo();
echo "ok";

print_r($_REQUEST);
print_r($_POST);
print_r($_GET);

include_once "include/sql.php";

function echec($message) {
//!!! à développer
if (isset($bdd))
  mysql_close($bdd);
die ("<div><dl><dt>Echec</dt><dd>$message</dd></dl></div>");	
}

//récupérer les données transmises
$lapin=$_REQUEST["lapin"];
$intitule=$_REQUEST["intitule"];
$texte=$_REQUEST["texte"];
$dest=$_REQUEST["id_profil"];
$auteur=$_REQUEST["sid"];	//!!! il s'agit en fait de l'id_profil transmis par la session

//echo "contrôle";


?>

<script>
function showReviews() {
  var ajaxGet;
  try {
    ajaxGet = new XMLHttpRequest();
    ajaxGet.open("GET", "reviewtest.xml", true);
    ajaxGet.setRequestHeader("Content-Type", "text/xml");
  }
  catch (err) {
    ajaxGet = new ActiveXObject("Microsoft.XMLHTTP");
    ajaxGet.open("GET", "reviewtest.xml", true);
    ajaxGet.setRequestHeader("Content-Type", "text/xml");
  }
	
  ajaxGet.onreadystatechange = function() {
    if (ajaxGet.readyState == 4) {
      var theResponse = ajaxGet.responseXML;
      var theRoot = theResponse.documentElement; // get the root element
      var tempYears = new Array;

      for(i = 0; i < theRoot.childNodes.length; i++){
        //// the problem is here: "theRoot.getElementsByTagName("reviewyear")[i] is undefined"
        tempYears[i] = theRoot.getElementsByTagName("review")[i].childNodes[0].nodeValue;
      }
    }
  }
  ajaxGet.send(null);
}

showReviews();
</script>