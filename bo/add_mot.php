<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

if (isset($_POST['texte_dico'])) {
	$nom_mot = stripslashes($_POST['texte_dico']);
} else {
	die("Pas de mot à ajouter !");
}
if (isset($_POST['setliste'])) {
	$nom_liste_mot = $_POST['setliste'];
} else {
	die("Pas de liste choisie !");
}
if (isset($_GET['return'])) {
	$url_return = $_GET['return'];
} else {
	$url_return = 'tableau_global.php';
}

$nom_mot = strtolower($nom_mot);
/* @var $mot Mot */
$mot = Root::returnIfNomAlreadyInMySQL($nom_mot, 'Mot');

if (isset($mot)) {
	
	$mot->loadFromMySQL();
	$nom_liste = $mot->returnNameOfListe();
	if ($nom_liste == 'LR_MOT') {
		$couleur_liste = 'rouge';
	} else {
		$couleur_liste = 'noire';
	}
	$message = "Le mot '$nom_mot' existe déjà en liste $couleur_liste.";
	$class = 'error';
} else {
	
	$mot = Mot::CreateInMySQL($nom_mot);
	$mot->loadFromMySQL();
	
	$liste = new Liste(new Key(array_search($nom_liste_mot, Config::$array_keys_noms_liste)));
	$liste->loadFromMySQL();
	
	$liste->addChild($mot);
	$liste->saveInMySQL();
	
	
	$liste_N_COM = new Liste(new Key(N_COM));
	$liste_N_COM->loadFromMySQL();
	$liste_A_COM = new Liste(new Key(A_COM));
	$liste_A_COM->loadFromMySQL();
	
	$commentaires_N_A = array_merge($liste_N_COM->getCommentaires(), $liste_A_COM->getCommentaires());
	foreach ($commentaires_N_A as $com) {
		/* @var $com Commentaire */
		$com->loadFromMySQL();
		$com->checkIfWordIsInCommentaireAndUpdateLRLN($mot);
		$com->saveInMySQL();
	}
	
	$mot->saveInMySQL();
	
	if ($nom_liste_mot == 'LR_MOT') {
		$couleur_liste = 'rouge';
	} else {
		$couleur_liste = 'noire';
	}
	$message = "Le mot '$nom_mot' a été ajouté à la liste $couleur_liste.";
	$class = 'success';
}

mysql_close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="1; url=<?php echo $url_return; ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><?php echo htmlentities($message); ?></title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body class="<?php echo $class; ?>">
<div class="<?php echo $class; ?>"><?php echo htmlentities($message); ?></div>
</body>
</html>