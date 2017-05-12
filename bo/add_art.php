<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

if (isset($_POST['site'])) { $site = $_POST['site']; } else { $site = ''; }
if (isset($_POST['rubrique'])) { $rubrique = $_POST['rubrique']; } else { $rubrique = ''; }
if (isset($_POST['titre'])) { $titre = stripslashes($_POST['titre']); } else { $titre = ''; }
if (isset($_POST['texte'])) { $texte = stripslashes($_POST['texte']); } else { $texte = ''; }

if ($titre == '') {
	$message = "Vous devez entrer un titre !";
	$class = 'error';
} else if ($texte == '') {
	$message = "Vous devez entrer un texte !";
	$class = 'error';
} else {
	$rub = new Rubrique(new Key($rubrique));
	$rub->loadFromMySQL();
	$article = Article::CreateInMySQL($titre, $texte);
	$article->loadFromMySQL();
	$article->addParent($rub);
	$article->saveInMySQL();
	$rub->saveInMySQL();
	
	$message = "L'article a bien été ajouté.";
	$class = 'success';
}

mysql_close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="2; url=ajouter_article.php" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><?php echo htmlentities($message); ?></title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body class="<?php echo $class; ?>">
<div class="<?php echo $class; ?>"><?php echo htmlentities($message); ?></div>
</body>
</html>