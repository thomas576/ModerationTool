<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

if (isset($_GET['key'])) {
	$key_str = $_GET['key'];
} else {
	die("Pas d'identifiant !");
}
if (isset($_GET['setliste'])) {
	$nom_new_liste = $_GET['setliste'];
} else {
	die("Pas de liste choisie !");
}
if (isset($_GET['return'])) {
	$url_return = $_GET['return'];
} else {
	$url_return = 'tableau_global.php';
}

$key = new Key($key_str);
$class_name = $key->getClassName();
/* @var $objet Root */
$objet = new $class_name($key);
$objet->loadFromMySQL();

/* @var $old_liste Liste */
$old_liste = $objet->getListe();
$old_liste->loadFromMySQL();

if ($nom_new_liste == 'OK_MOT') {
	
	$old_liste->deleteChild($objet);
	$old_liste->saveInMySQL();
	
	$commentaires = $objet->getCommentaires();
	foreach ($commentaires as $com) {
		$com->loadFromMySQL();
		$com->updateLRLN($objet, $old_liste, null);
		$com->deleteChild($objet);
		$com->deleteParent($objet);
		$com->saveInMySQL();
	}
	
	Mot::deleteMotFromMySQL($objet);
	
} else {

	$key_new_liste_str = array_search($nom_new_liste, Config::$array_keys_noms_liste);
	$key_new_liste = new Key($key_new_liste_str);
	$new_liste = new Liste($key_new_liste);
	$new_liste->loadFromMySQL();
	
	if ($nom_new_liste != $old_liste->getNom()) {
		
		$old_liste->deleteChild($objet);
		$new_liste->addChild($objet);
		
		$old_liste->saveInMySQL();
		$new_liste->saveInMySQL();
		
		if ($class_name == 'Commentaire') {
			
			$article = $objet->getArticle();
			$article->loadFromMySQL();
			$article->updateNAVR($new_liste, $old_liste);
			$article->saveInMySQL();
			$rubrique = $article->getRubrique();
			$rubrique->loadFromMySQL();
			$rubrique->updateNAVR($new_liste, $old_liste);
			$rubrique->saveInMySQL();
			$site = $rubrique->getSite();
			$site->loadFromMySQL();
			$site->updateNAVR($new_liste, $old_liste);
			$site->saveInMySQL();
			
			$pseudo = $objet->getPseudo();
			$pseudo->loadFromMySQL();
			$pseudo->updateNAVR($new_liste, $old_liste);
			$pseudo->saveInMySQL();
			
			$email = $objet->getEmail();
			$email->loadFromMySQL();
			$email->updateNAVR($new_liste, $old_liste);
			$email->saveInMySQL();
			
			$ip = $objet->getIp();
			$ip->loadFromMySQL();
			$ip->updateNAVR($new_liste, $old_liste);
			$ip->saveInMySQL();
			
			if ($nom_new_liste == 'N_COM' || $nom_new_liste == 'A_COM') {
				$objet->checkForWordsListedAndCalculateLRLN();
			}
			
		} else if ($class_name == 'Mot') {
			
			$commentaires = $objet->getCommentaires();
			foreach ($commentaires as $com) {
				$com->loadFromMySQL();
				$com->updateLRLN($objet, $old_liste, $new_liste);
				$com->saveInMySQL();
			}
			
		}
		
		$objet->saveInMySQL();
		
	}
}

header('Location: '.$url_return);

mysql_close();

?>