<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

if (isset($_GET['auteur'])) {
	$key_auteur_str = $_GET['auteur'];
} else {
	die("Pas d'identifiant !");
}

$key_auteur = new Key($key_auteur_str);
$class_name = $key_auteur->getClassName();
/* @var $auteur Auteur */
$auteur = new $class_name($key_auteur);
$auteur->loadFromMySQL();

$colored_name_auteur = $auteur->returnNomInColor(false);
$name_auteur = htmlentities($auteur->getNom());

$NAVR = $auteur->getNAVR(); $N = $NAVR['N']; $A = $NAVR['A']; $V = $NAVR['V']; $R = $NAVR['R']; 
$total = $N + $A + $V + $R;
if ($total > 0) {
	$N_percent = round(($N / $total) * 100);
	$A_percent = round(($A / $total) * 100);
	$V_percent = round(($V / $total) * 100);
	$R_percent = 100 - $N_percent - $A_percent - $V_percent;
} else {
	$N_percent = ''; $A_percent = ''; $V_percent = ''; $R_percent = '';
}
$statistiques = 'Depuis sa premi&egrave;re apparition le <em><strong>'.date('d/m/y', $auteur->getTime()).' &agrave; '.date('H\hi', $auteur->getTime()).'</strong></em>, '.$name_auteur.' a publi&eacute; <strong>'.$total.'</strong> commentaire(s) dont : '.$N.' &quot;nouveau&quot; ('.$N_percent.'%), '.$A.' &quot;en attente&quot; ('.$A_percent.'%), '.$V.' &quot;valid&eacute;&quot; ('.$V_percent.'%), '.$R.' &quot;rejet&eacute;&quot; ('.$R_percent.'%).';


$commentaires = $auteur->getCommentaires();
$rows = '';
$array_mots_used = array();
foreach ($commentaires as $com) {
	/* @var $com Commentaire */
	$com->loadFromMySQL();
	$rows .= $com->returnRowTableau('infos_auteur');
	$array_mots_used = array_merge($array_mots_used, array_keys($com->getChildren()));
}

$array_mots_used = array_unique($array_mots_used);
$html_mots = '';
$virgule = '';
foreach ($array_mots_used as $key_mot_str) {
	$mot = new Mot(new Key($key_mot_str));
	$mot->loadFromMySQL();
	$html_mots .= $virgule.$mot->returnNomInColor();
	$virgule = ', ';
}


function remplirContenuParagraphe($array_auteur, &$paragraphes) {
	$virgule = '';
	foreach ($array_auteur as $aut) {
		/* @var $aut Auteur */
		$aut->loadFromMySQL();
		$paragraphes .= $virgule.$aut->returnNomInColor(true);
		$virgule = ', ';
	}
}

$paragraphes = '';
if ($class_name != 'Pseudo') {
	$paragraphes .= '<h2>Pseudonymes employ&eacute;s par '.$name_auteur.' :</h2>';
	$paragraphes .= '<div id="infos_pseudos">';
	remplirContenuParagraphe($auteur->getPseudos(), $paragraphes);
	$paragraphes .= '</div>';
}
if ($class_name != 'Email') {
	$paragraphes .= '<h2>Emails associ&eacute;s &agrave; '.$name_auteur.' :</h2>';
	$paragraphes .= '<div id="infos_emails">';
	remplirContenuParagraphe($auteur->getEmails(), $paragraphes);
	$paragraphes .= '</div>';
}
if ($class_name != 'Ip') {
	$paragraphes .= '<h2>Adresses IP associ&eacute;es &agrave; '.$name_auteur.' :</h2>';
	$paragraphes .= '<div id="infos_ips">';
	remplirContenuParagraphe($auteur->getIps(), $paragraphes);
	$paragraphes .= '</div>';
}

$nom_liste = $auteur->returnNameOfListe();
if ($class_name == 'Pseudo') {
	$suffixe = '_PSE';
	$phrase_action = "ce pseudo";
} else if ($class_name == 'Email') {
	$suffixe = '_EMA';
	$phrase_action = "cet email";
} else if ($class_name == 'Ip') {
	$suffixe = '_IPA';
	$phrase_action = "cette adresse IP";
}

$boutons = '';
$espace = '';
foreach (Config::$array_listes_mots as $prefixe => $detail) {
	$nom = $prefixe.$suffixe;
	if ($nom_liste != $nom) {
		$boutons .= $espace.'<div id="bouton_'.$prefixe.'"><a href="change_liste.php?key='.$key_auteur_str.'&setliste='.$nom.'&return='.urlencode($_SERVER['REQUEST_URI']).'"><span>&gt;&gt; '.htmlentities($detail).'</span><img src="images/'.$prefixe.'.png" alt="Placer ce pseudo en '.htmlentities(strtolower($detail)).'" width="35" height="35" border="0" /></a></div>';
		$espace = '&nbsp;&nbsp;';
	}
}

mysql_close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Informations sur <?php echo $name_auteur; ?></title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">

function chargement() {
	preloadImages();
}

function preloadImages() {
     var i = 0;
     
     imageObj = new Image();
     
     images = new Array("images/N.png", "images/A.png", "images/V.png", "images/R.png");
     
     for (i=0; i<=3; i++) {
          imageObj.src=images[i];
     }
}

</script>
</head>

<body class="infos_<?php echo $class_name; ?>" onload="chargement();">
<h1>Informations sur <?php echo $colored_name_auteur; ?></h1>
<h2>Historique des commentaires de <?php echo $name_auteur; ?> :</h2>
<div id="infos_statistiques"><?php echo $statistiques; ?></div>
<div id="infos_commentaires">
<table width="100%">
  <tr class="tr_commentaire">
	<th class="titre" scope="col">Titre</th>
	<th class="texte" scope="col">Commentaire</th>
	<th class="date" scope="col">Date</th>
	<th class="mots" scope="col">Mots rep&eacute;r&eacute;s</th>
	<th class="statut" scope="col">Statut</th>
	<th class="remarque" scope="col">Remarque</th>
  </tr>
  <?php echo $rows; ?>
</table>
</div>
<?php echo $paragraphes; ?>
<h2>Mots en liste rouge/noire employ&eacute;s par <?php echo $name_auteur; ?> :</h2>
<div id="infos_mots"><?php echo $html_mots; ?></div>
<div id="infos_action">
<h3>Action sur <?php echo $phrase_action; ?> : </h3><div class="boutons_action"><?php echo $boutons; ?></div>
</div>
</body>
</html>
