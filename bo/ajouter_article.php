<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

if (isset($_GET['action'])) { $action = $_GET['action']; } else { $action = ''; }

if ($action == 'listerrubriques') {
	
	if (isset($_GET['keysite'])) { $keysite = $_GET['keysite']; } else { $keysite = ''; }
	$site = new Site(new Key($keysite));
	$site->loadFromMySQL();
	echo $site->returnSelectChildrenHTML();
	die();
	
}

$key_site = 'sit-1';
$site = new Site(new Key($key_site));
$site->loadFromMySQL();
$select_sites = $site->returnOptionLigneHTML(true);
$select_rubriques = $site->returnSelectChildrenHTML();

mysql_close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Ajouter un article</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">
function getXHR() {
	var xhr = null;
	
	if (window.XMLHttpRequest) xhr = new XMLHttpRequest(); // Firefox et autres
	else if(window.ActiveXObject){ // Internet Explorer 
		   try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
	} else { // XMLHttpRequest non supporté par le navigateur 
		alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
		xhr = false;
	}
	
	return xhr;
}

function listerrubriques() {
	
	var xhr = getXHR();
	
	xhr.onreadystatechange = function() { 
         if (xhr.readyState == 4) {
             if (xhr.status == 200) {
             	// on affiche les résultats
             	document.getElementById('rubrique').innerHTML = xhr.responseText;
		}
         }
   	}; 
	
	var keysite = document.getElementById('site').value;

	xhr.open("GET", "<?php echo $_SERVER['PHP_SELF']; ?>?action=listerrubriques&keysite=" + keysite, true);     
 	xhr.send(null);
	
	return false;
}
</script>
</head>

<body>
<h1>Ajouter un article </h1>
<form action="add_art.php" method="post" id="ajouter_article">
  <p>Site : 
    <select name="site" id="site" onchange="listerrubriques();">
      <?php echo $select_sites; ?>
    </select>
    dans la rubrique : 
    <select name="rubrique" id="rubrique">
    	<?php echo $select_rubriques; ?>
    </select>
  </p>
  <p>Titre : 
    <input name="titre" id="titre" type="text" size="100" maxlength="200" />
  </p>
  <p>Texte : <br />
    <textarea name="texte" id="texte" cols="80" rows="25"></textarea>
  </p>
  <p id="action">
    <input type="reset" name="Reset" value="R&eacute;tablir" /> ou <input type="submit" name="Submit" value="Ajouter l'article" />
  </p>
</form>
</body>
</html>