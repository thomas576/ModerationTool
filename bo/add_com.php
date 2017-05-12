<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

if (isset($_GET['article'])) {
	$key_article = $_GET['article'];
} else {
	die("Pas d'article demandé !");
}

if (isset($_POST['titre'])) { $titre = stripslashes($_POST['titre']); } else { $titre = ''; }
if (isset($_POST['texte'])) { $texte = stripslashes($_POST['texte']); } else { $texte = ''; }
if (isset($_POST['pseudo'])) { $pseudo = stripslashes($_POST['pseudo']); } else { $pseudo = ''; }
if (isset($_POST['email'])) { $email = stripslashes($_POST['email']); } else { $email = ''; }
if (isset($_POST['accepter'])) { $accepter = stripslashes($_POST['accepter']); } else { $accepter = ''; }

$class = 'error';
if ($texte == '') {
	$message = "Vous devez entrer un texte ! Commentaire refusé.";
} else if ($pseudo == '') {
	$message = "Vous devez entrer votre pseudonyme ! Commentaire refusé.";
} else if (strpos($email, '@') === false) {
	$message = "Vous devez entrer une adresse e-mail valide ! Commentaire refusé.";
} else if ($accepter != 'oui') {
	$message = "Vous devez accepter de publier votre commentaire ! Commentaire refusé.";
} else {
	if ($titre == '') {
		$titre = '(sans titre)';
	}
	
	$com = Commentaire::CreateInMySQL($titre, $texte);
	$com->loadFromMySQL();
	
	// On ajoute le parent : Liste Nouveau
	$N_COM = new Liste(new Key(N_COM));
	$N_COM->loadFromMySQL();
	$com->addParent($N_COM);
	$N_COM->saveInMySQL();
	
	// On ajoute le parent : article, et on update les NAVR
	$article = new Article(new Key($key_article));
	$article->loadFromMySQL();
	$com->addParent($article);
	$article->updateNAVR($N_COM);
	$article->saveInMySQL();
	$rubrique = $article->getRubrique();
	$rubrique->loadFromMySQL();
	$rubrique->updateNAVR($N_COM);
	$rubrique->saveInMySQL();
	$site = $rubrique->getSite();
	$site->loadFromMySQL();
	$site->updateNAVR($N_COM);
	$site->saveInMySQL();
	
	// On charge ou on crée le pseudo, l'email et l'ip
	$pse = Pseudo::returnIfNomAlreadyInMySQL($pseudo, 'Pseudo');
	if (!isset($pse)) {
		$pse = Pseudo::CreateInMySQL($pseudo);
		$pse->loadFromMySQL();
		$OK_PSE = new Liste(new Key(OK_PSE));
		$OK_PSE->loadFromMySQL();
		$pse->addParent($OK_PSE);
		$OK_PSE->saveInMySQL();
	} else {
		$pse->loadFromMySQL();
	}
	$ema = Email::returnIfNomAlreadyInMySQL($email, 'Email');
	if (!isset($ema)) {
		$ema = Email::CreateInMySQL($email);
		$ema->loadFromMySQL();
		$OK_EMA = new Liste(new Key(OK_EMA));
		$OK_EMA->loadFromMySQL();
		$ema->addParent($OK_EMA);
		$OK_EMA->saveInMySQL();
	} else {
		$ema->loadFromMySQL();
	}
	$ip_client = $_SERVER['REMOTE_ADDR'];
	$ipa = Ip::returnIfNomAlreadyInMySQL($ip_client, 'Ip');
	if (!isset($ipa)) {
		$ipa = Ip::CreateInMySQL($ip_client);
		$ipa->loadFromMySQL();
		$OK_IPA = new Liste(new Key(OK_IPA));
		$OK_IPA->loadFromMySQL();
		$ipa->addParent($OK_IPA);
		$OK_IPA->saveInMySQL();
	} else {
		$ipa->loadFromMySQL();
	}
	
	// On fait les liens entre le pseudo, l'email et l'ip
	$pse->addParent($ema);
	$ema->addParent($ipa);
	$ipa->addParent($pse);
	
	// On fait les liens entre pseudo/email/ip et le Commentaire et on update les NAVR
	$pse->addChild($com);
	$ema->addChild($com);
	$ipa->addChild($com);
	$pse->updateNAVR($N_COM);
	$ema->updateNAVR($N_COM);
	$ipa->updateNAVR($N_COM);
	
	// On enregistre le pseudo, l'email et l'ip
	$pse->saveInMySQL();
	$ema->saveInMySQL();
	$ipa->saveInMySQL();
	
	// On cherche les mots blacklistés dans le commentaire, on calcule les LNLR et on fait un lien vers eux
	$com->checkForWordsListedAndCalculateLRLN();
	
	// On enregistre le tout
	$com->saveInMySQL();
	
	$message = "Votre commentaire a bien été enregistré.";
	$class = 'success';
}

mysql_close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="2; url=ajouter_commentaire.php?article=<?php echo $key_article; ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><?php echo htmlentities($message); ?></title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body class="<?php echo $class; ?>">
<div class="<?php echo $class; ?>"><?php echo htmlentities($message); ?></div>
</body>
</html>