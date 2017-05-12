<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

if (isset($_GET['liste'])) { 
	$nom_liste = $_GET['liste']; 
} else { 
	$nom_liste = 'LR_MOT';
}

$LR_LN = substr($nom_liste, 0, 2);

$liste = new Liste(new Key(array_search($nom_liste, Config::$array_keys_noms_liste)));
$liste->loadFromMySQL();

$array_couleur_suffixe = array('Rouge' => 'LR', 'Noire' => 'LN');
$select_rouge_noire = '';
foreach ($array_couleur_suffixe as $couleur => $prefixe) {
	if ($LR_LN == $prefixe) {
		$select_rouge_noire .= '<div id="'.strtolower($couleur).'" class="inset">'.$couleur.'</div>';
	} else {
		$select_rouge_noire .= '<a href="liste_mots.php?liste='.$prefixe.'_MOT"><div id="'.strtolower($couleur).'" class="outset">'.$couleur.'</div></a>';
	}
}

$h2 = 'Mots en '.strtolower(Config::$array_listes_mots[$LR_LN]).' :';

$array_mots = array_values($liste->getChildren());
foreach ($array_mots as $mot) {
	/* @var $mot Mot */
	$mot->loadFromMySQL();
}
usort($array_mots, array('Mot', 'sortByNom'));

$rows = '';
foreach ($array_mots as $mot) {
	$rows .= $mot->returnRowInListe();
}

mysql_close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Mots list&eacute;s</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">

function chargement() {
	preloadImages();
}

function preloadImages() {
     var i = 0;
     
     imageObj = new Image();
     
     images = new Array("images/suppr.png", "images/LR.png", "images/LN.png");
     
     for (i=0; i<=2; i++) {
          imageObj.src=images[i];
     }
}

</script>
</head>

<body onload="chargement();">
<h1>Mots list&eacute;s</h1>
<div id="select_liste">
<fieldset>
	<legend>S&eacute;l&eacute;ction de la liste &agrave; afficher :</legend>
	<div id="boutons_select">
		Mots en liste 
		<div id="select_rouge_noire"><?php echo $select_rouge_noire; ?></div>
	</div>
</fieldset>
</div>
<h2><?php echo $h2; ?></h2>
<div id="liste_mots">
	<table width="100%">
	  <tr class="tr_head">
		<th class="mot" scope="col">Mot ou expression</th>
		<th class="nbr_com_mot" scope="col">Pr&eacute;sent dans :</th>
		<th class="change_liste" scope="col">Changer de liste</th>
		<th class="suppr" scope="col">Supprimer</th>
	  </tr>
	  <?php echo $rows; ?>
	</table>
</div>
</body>
</html>