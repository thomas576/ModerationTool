<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

session_start();

if (isset($_SESSION['target'])) {
	$key_target_str = $_SESSION['target'];
} else {
	$key_target_str = '';
}

if (isset($_GET['target'])) {
	if ($_GET['target'] != '') {
		$key_target_str = $_GET['target'];
	}
}

if ($key_target_str == '') {
	$string_keys = '';
} else {
	$key_target = new Key($key_target_str);
	$class_name = $key_target->getClassName();
	/* @var $target Root */
	$target = new $class_name($key_target);
	$target->loadFromMySQL();
	
	$array_parents = $target->getParents();
	$string_keys = '"'.$key_target_str.'"';
	while (count($array_parents) > 0) {
		/* @var $parent Root */
		$array_values = array_values($array_parents);
		$parent = $array_values[0];
		$parent->loadFromMySQL();
		$string_keys = '"'.$parent->getKey()->getKeyString().'", '.$string_keys;
		$array_parents = $parent->getParents();
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Tableau global</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">

function chargement() {
	preloadImages();
	openNextTR();
}

function preloadImages() {
     var i = 0;
     
     imageObj = new Image();
     
     images = new Array("images/N.png", "images/A.png", "images/V.png", "images/R.png");
     
     for (i=0; i<=3; i++) {
          imageObj.src=images[i];
     }
}

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

var id_global = '';

function displayTable(id) {
	
	var tr = document.getElementById(id);
	if (tr.style.display == "table-row") {
		tr.style.display = "none";
		return;
	}
	
	var xhr = getXHR();
	id_global = id;
	
	xhr.onreadystatechange = function() { 
         if (xhr.readyState == 4) {
            if (xhr.status == 200) {
             	// on affiche les résultats
             	var tr = document.getElementById(id_global);
             	var td = tr.firstChild;
             	td.innerHTML = xhr.responseText;
             	tr.style.display = "table-row";
             	openNextTR();
		}
         }
   	}; 

	xhr.open("GET", "render_table.php?parent=" + id, true);     
 	xhr.send(null);
 	
}

var keys = new Array(<?php echo $string_keys; ?>);
var a = 0;

function openNextTR() {
	if (a < keys.length) {
		a = a + 1;
		displayTable(keys[a - 1]);
	}
}

</script>
</head>

<body onload="chargement();">
<h1>Gestion des commentaires</h1>
<div id="tableau_global">
  <?php include_once 'render_table.php'; ?>
</div>
</body>
</html>