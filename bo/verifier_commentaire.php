<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

if (isset($_GET['com'])) {
	$key_commentaire_str = $_GET['com'];
} else {
	die("Pas de commentaire demandé !");
}

$key_commentaire = new Key($key_commentaire_str);
$class_name = $key_commentaire->getClassName();
/* @var $commentaire Commentaire */
$commentaire = new $class_name($key_commentaire);
$commentaire->loadFromMySQL();

$remarque = $commentaire->getRemarque();

$article = $commentaire->getArticle();
$article->loadFromMySQL();
$rubrique = $article->getRubrique();
$rubrique->loadFromMySQL();
$site = $rubrique->getSite();
$site->loadFromMySQL();

$coms_article = $article->getCommentaires();
$total_coms = count($coms_article);
$numero = 0;
$key_next_com_str = '';
$key_previous_com_str = '';
for ($a = 0; $a < $total_coms; $a++) {
	if ($coms_article[$a]->getKey()->getKeyString() == $key_commentaire_str) {
		$numero = $a + 1;
		if ($numero > 1) {
			$key_previous_com_str = $coms_article[$a - 1]->getKey()->getKeyString();
		}
		if ($numero < $total_coms) {
			$key_next_com_str = $coms_article[$a + 1]->getKey()->getKeyString();
		}
		break;
	}
}
$navigation = '';
if ($key_previous_com_str != '') {
	$navigation .= '<div id="nav_prec"><a href="verifier_commentaire.php?com='.$key_previous_com_str.'">&lt;&lt; commentaire pr&eacute;c&eacute;dant </a></div>';
}
$navigation .= '<div id="nav_retour"><a href="tableau_global.php?target='.$article->getKey()->getKeyString().'">Retour au tableau global </a></div>';
if ($key_next_com_str != '') {
	$navigation .= '<div id="nav_suiv"><a href="verifier_commentaire.php?com='.$key_next_com_str.'">commentaire suivant &gt;&gt; </a></div>';
}

$nom_liste = $commentaire->returnNameOfListe();
$boutons_action = '';
foreach (Config::$array_statuts_commentaires as $lettre => $detail) {
	if (stripos($nom_liste, $lettre) !== false) {
		$lettre_liste = $lettre;
		$etiquette = '<div id="etiquette"><div class="etiquette_'.$lettre_liste.'"><img src="images/'.$lettre_liste.'-big.png" alt="'.htmlentities($detail).'" width="30" height="30" /><span>'.htmlentities($detail).'</span></div></div>';
	} else {
		$boutons_action .= '<div id="bouton_'.$lettre.'"><a href="javascript:changeListeCommentaire(\''.$lettre.'_COM\')" onmouseout="document.getElementById(\'image_'.$lettre.'\').src=\'images/'.$lettre.'-hidden.png\'" onmouseover="document.getElementById(\'image_'.$lettre.'\').src=\'images/'.$lettre.'.png\'"><span>'.htmlentities($detail).'</span><img id="image_'.$lettre.'" src="images/'.$lettre.'-hidden.png" alt="D&eacute;finir ce statut comme '.htmlentities(strtolower($detail)).'" width="25" height="25" border="0" /></a></div>';
	}
}

$pseudo = $commentaire->getPseudo();
$pseudo->loadFromMySQL();

$email = $commentaire->getEmail();
$email->loadFromMySQL();

$ip = $commentaire->getIp();
$ip->loadFromMySQL();

$array_auteur = array('pseudo' => $pseudo, 'email' => $email, 'ip' => $ip);
$array_suffixes = array('pseudo' => '_PSE', 'email' => '_EMA', 'ip' => '_IPA');
$html_auteur = '';
foreach ($array_auteur as $type => $auteur) {
	/* @var $auteur Auteur */
	$boutons_auteur = '';
	foreach (Config::$array_listes_mots as $prefixe => $detail) {
		if (stripos($auteur->returnNameOfListe(), $prefixe) === false) {
			$boutons_auteur .= '<div class="bouton_'.$prefixe.'"><a href="change_liste.php?key='.$auteur->getKey()->getKeyString().'&setliste='.$prefixe.$array_suffixes[$type].'&return='.urlencode($_SERVER['REQUEST_URI']).'"><span> &gt;&gt;</span><img src="images/'.$prefixe.'.png" alt="'.htmlentities($detail).'" width="21" height="21" border="0" /></a></div>';
		}
	}
	$html_auteur .= '<div id="'.$type.'">'.$auteur->returnNomInColor().$boutons_auteur.'</div>';
}

$titreInColor = $commentaire->returnTitreWithColorSpan();
$texteInColor = $commentaire->returnTexteWithColorSpan();

mysql_close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Commentaire</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

function chargement() {
	preloadImages();
	actualiseSelection();
}

function preloadImages() {
     var i = 0;
     
     imageObj = new Image();
     
     images = new Array("images/N.png", "images/A.png", "images/V.png", "images/R.png");
     
     for (i=0; i<=3; i++) {
          imageObj.src=images[i];
     }
}

function getSelectedText() {
	if (window.getSelection) {
		var str = window.getSelection();
	} else if (document.getSelection) {
		var str = document.getSelection();
	} else {
		var str = document.selection.createRange().text;
	}
	return str;
}

function actualiseSelection() {
	var newSelectedText = getSelectedText();
	var inputTextDico = document.getElementById('texte_dico');
	if (newSelectedText != "" && newSelectedText != inputTextDico.value) {
		inputTextDico.value = newSelectedText;
	}
	setTimeout("actualiseSelection()", 100);
}

function changeListeCommentaire(liste) {
	document.getElementById('setliste').value = liste;
	document.getElementById('form2').submit();
}

</script>
</head>

<body class="<?php echo $lettre_liste; ?>" onload="chargement();">
<h1>Commentaire n&deg;<?php echo $numero.'/'.$total_coms; ?> </h1>
  <?php echo $etiquette; ?>
<div id="verifier_commentaire">
	<div id="lien_article"><span id="site"><?php echo htmlentities($site->getNom()); ?></span> &gt;&gt; <span id="rubrique"><?php echo htmlentities($rubrique->getNom()); ?></span> &gt;&gt; <span id="titre_article"><?php echo htmlentities($article->getNom()); ?></span> (<a href="ajouter_commentaire.php?article=<?php echo $article->getKey()->getKeyString(); ?>" target="_blank" class="lien_article">Voir l'article</a>)</div>
  	<h2>Commentaire de <?php echo htmlentities($pseudo->getNom()); ?> :</h2>
  	<div id="auteur">
    		<?php echo $html_auteur; ?>
    	</div>
	<fieldset id="commentaire">
		<legend id="titre_com"><?php echo $titreInColor; ?></legend>
		<div id="texte_com">
			<p><?php echo $texteInColor; ?></p>
		</div>
	</fieldset>
    
	<form id="form1" name="form1" method="post" action="add_mot.php?return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">
  	<div id="add_dictionnaire">Ajouter au dictionnaire : 
	  <input name="texte_dico" type="text" id="texte_dico" size="40" />
	  <input type="submit" name="bouton_dico_LR" id="bouton_dico_LR" class="LR" value="&gt;&gt; liste rouge" onclick="document.getElementById('liste_dico').value = 'LR_MOT';" />
	  <input type="submit" name="bouton_dico_LN" id="bouton_dico_LN" class="LN" value="&gt;&gt; liste noire" onclick="document.getElementById('liste_dico').value = 'LN_MOT';" />
	  
	  <input type="hidden" name="setliste" id="liste_dico" value="" />
    </div>
    </form>
    
     <div id="action_statut">
     	<h3>Action sur ce commentaire : </h3>
        <div id="action_statut_boutons">
        	<div id="faire_remarque">Joindre une remarque :<br />
	        	<form id="form2" name="form2" method="post" action="change_remarque.php?key=<?php echo $key_commentaire_str; ?>&return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">
	        		<textarea name="remarque" cols="20" rows="2" id="remarque"><?php echo htmlentities($remarque); ?></textarea>
	        		<input type="hidden" name="setliste" id="setliste" value="" />
	        	</form>
        	</div>
        	<?php echo $boutons_action; ?>
        </div>
     </div>
    
	<div id="navigation">
	  <?php echo $navigation; ?>
	</div>

</div>
</body>
</html>