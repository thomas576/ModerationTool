<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

if (isset($_GET['article'])) {
	$key_article = $_GET['article'];
} else {
	die("Pas d'article demandé !");
}

$article = new Article(new Key($key_article));
$article->loadFromMySQL();

$rubrique = $article->getRubrique();
$rubrique->loadFromMySQL();

$site = $rubrique->getSite();
$site->loadFromMySQL();

$array_commentaires = $article->getCommentaires();
$html_commentaires = '';
foreach ($array_commentaires as $commentaire) {
	/* @var $commentaire Commentaire */
	$commentaire->loadFromMySQL();
	$key_liste_str = $commentaire->getListe()->getKey()->getKeyString();
	if ($key_liste_str != R_COM) {
		$html_commentaires .= $commentaire->returnRowInSimpleDisplayHTML();
	}
}

mysql_close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Ajouter un commentaire</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<h1>Article :</h1>
<div id="article">
	<div id="journal"><span id="site"><?php echo htmlentities($site->getNom()); ?></span> &gt;&gt; <span id="rubrique"><?php echo htmlentities($rubrique->getNom()); ?></span></div>
	<h2 id="titre"><?php echo htmlentities($article->getNom()); ?></h2>
	<div id="texte"><?php echo htmlentities($article->getTexte()); ?></div>
</div>
<h2>Commentaires :</h2>
<div id="affichage_commentaires">
	<table class="table_com" width="100%" align="center">
	  <tr>
	    <th scope="col">De : </th>
	    <th scope="col">Titre : </th>
	    <th scope="col">Commentaire : </th>
	    <th scope="col"></th>
	  </tr>
	  <?php echo $html_commentaires; ?>
	</table>
</div>
<h2>Ajouter un commentaire :</h2>
<div id="formaulaire_commentaires">
	<form action="add_com.php?article=<?php echo $key_article; ?>" method="post" name="form_commentaire" id="ajouter_commentaire">
	  <p>Titre : 
	    <input name="titre" type="text" id="titre" size="100" maxlength="200" />
	  </p>
	  <p>Texte : <br />
	    <textarea name="texte" cols="60" rows="5" id="texte"></textarea>
	  </p>
	  <p>Votre pseudo : 
	    <input name="pseudo" type="text" id="pseudo" />
	  et votre e-mail : 
	  <input name="email" type="text" id="email" size="30" />
	  </p>
	  <p>
	    <input type="checkbox" id="accepter" name="accepter" value="oui" checked="checked" />
	  J'accepte que mon commentaire soit publi&eacute; dans <?php echo htmlentities($site->getNom()); ?>. 
	  <span id="action">
		  <input name="Reset" type="reset" id="Reset" value="R&eacute;initialiser" />
		  ou 
		  <input type="submit" name="Submit" value="Valider" />
	  </span>
	  </p>
	</form>
</div>
</body>
</html>