<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

if (isset($_GET['liste'])) { 
	$nom_liste = $_GET['liste']; 
} else { 
	$nom_liste = 'LR_PSE';
}
$arr = explode('_', $nom_liste);
$LR_LN = $arr[0];
$PSE_EMA_IPA = $arr[1];

$liste = new Liste(new Key(array_search($nom_liste, Config::$array_keys_noms_liste)));
$liste->loadFromMySQL();

$array_classes_suffixe = array('Pseudo' => 'PSE', 'Email' => 'EMA', 'Ip' => 'IPA');
$class_name = array_search($PSE_EMA_IPA, $array_classes_suffixe);

$array_auteurs = array_values($liste->getChildren());
foreach ($array_auteurs as $auteur) {
	/* @var $auteur Auteur */
	$auteur->loadFromMySQL();
}
usort($array_auteurs, array('Auteur', 'sortByNom'));

$rows = '';
foreach ($array_auteurs as $auteur) {
	$rows .= $auteur->returnRowInListe();
}

$h2 = $class_name.'s en '.strtolower(Config::$array_listes_mots[$LR_LN]).' :';

$select_auteur = '';
foreach ($array_classes_suffixe as $class => $suffixe) {
	if ($PSE_EMA_IPA == $suffixe) {
		$select_auteur .= '<div id="'.strtolower($class).'" class="inset">'.$class.'s</div>';
	} else {
		$select_auteur .= '<a href="liste_auteurs.php?liste='.$LR_LN.'_'.$suffixe.'"><div id="'.strtolower($class).'" class="outset">'.$class.'s</div></a>';
	}
}

$array_couleur_suffixe = array('Rouge' => 'LR', 'Noire' => 'LN');
$select_rouge_noire = '';
foreach ($array_couleur_suffixe as $couleur => $prefixe) {
	if ($LR_LN == $prefixe) {
		$select_rouge_noire .= '<div id="'.strtolower($couleur).'" class="inset">'.$couleur.'</div>';
	} else {
		$select_rouge_noire .= '<a href="liste_auteurs.php?liste='.$prefixe.'_'.$PSE_EMA_IPA.'"><div id="'.strtolower($couleur).'" class="outset">'.$couleur.'</div></a>';
	}
}

mysql_close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Utilisateurs list&eacute;s</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">

function chargement() {
	preloadImages();
}

function preloadImages() {
     var i = 0;
     
     imageObj = new Image();
     
     images = new Array("images/OK.png", "images/LR.png", "images/LN.png");
     
     for (i=0; i<=2; i++) {
          imageObj.src=images[i];
     }
}

</script>
</head>

<body onload="chargement();">
<h1>Utilisateurs list&eacute;s</h1>
<div id="select_liste">
<fieldset>
	<legend>S&eacute;l&eacute;ction de la liste &agrave; afficher :</legend>
	<div id="boutons_select">
		<div id="select_auteur"><?php echo $select_auteur; ?></div> 
		en liste 
		<div id="select_rouge_noire"><?php echo $select_rouge_noire; ?></div>
	</div>
</fieldset>
</div>
<h2><?php echo $h2; ?></h2>
<div id="liste_auteurs">
	<table width="100%">
	  <tr class="tr_head">
		<th class="<?php echo strtolower($class_name); ?>" scope="col"><?php echo $class_name; ?></th>
		<th class="nbr_com" scope="col">Total commentaires</th>
		<th class="separator" scope="col"></th>
		<th class="N" scope="col">Nouveaux</th>
		<th class="A" scope="col">En attente</th>
		<th class="separator" scope="col"></th>
		<th class="V" scope="col">Valid&eacute;s</th>
		<th class="R" scope="col">Rejet&eacute;s</th>
		<th class="change_liste" scope="col">Changer de liste</th>
	  </tr>
	  <?php echo $rows; ?>
	</table>
</div>
</body>
</html>