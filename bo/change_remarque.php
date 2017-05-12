<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

if (isset ( $_GET['key'] )) {
	$key_str = $_GET['key'];
} else {
	die ( "Pas d'identifiant !" );
}
if (isset ( $_POST['setliste'] )) {
	$nom_new_liste = $_POST['setliste'];
} else {
	die ( "Pas de liste choisie !" );
}
if (isset ( $_GET['return'] )) {
	$url_return = $_GET['return'];
} else {
	$url_return = 'tableau_global.php';
}
if (isset($_POST['remarque'])) {
	$remarque = stripslashes ($_POST['remarque']);
} else {
	$remarque = '';
}

$key = new Key($key_str);
$commentaire = new Commentaire($key);
$commentaire->loadFromMySQL();

$commentaire->setRemarque($remarque);
$commentaire->saveInMySQL();

header('Location: change_liste.php?key='.$key_str.'&setliste='.$nom_new_liste.'&return='.urlencode($url_return));

mysql_close ();

?>