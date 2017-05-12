<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

if (isset($_GET['com'])) {
	$key_com_str = $_GET['com'];
} else {
	die("Pas d'identifiant du commentaire !");
}

$key = new Key($key_com_str);
$commentaire = new Commentaire($key);
$commentaire->loadFromMySQL();
$commentaire->setRemarque("Alerte d'un lecteur");
$commentaire->saveInMySQL();

$pseudo = $commentaire->getPseudo();
$pseudo->loadFromMySQL();

$article = $commentaire->getArticle();
$key_art_str = $article->getKey()->getKeyString();
$url_return = 'ajouter_commentaire.php?article='.$key_art_str;
$url_redirection = 'change_liste.php?key='.$key_com_str.'&setliste=A_COM&return='.urlencode($url_return);

$message = "Votre alerte a été prise en compte concernant le commentaire de ".$pseudo->getNom().".";
$class = 'success';

mysql_close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="1; url=<?php echo $url_redirection; ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><?php echo htmlentities($message); ?></title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body class="<?php echo $class; ?>">
<div class="<?php echo $class; ?>"><?php echo htmlentities($message); ?></div>
</body>
</html>