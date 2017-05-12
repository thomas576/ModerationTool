<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

$site = new Site(new Key('sit-1'));
$site->loadFromMySQL();
$rubriques = array('Politique', 'International', 'Sports', '�conomie', 'Culture', 'High-Tech', 'Sciences', 'Emploi', 'Voyages', 'D�bats', 'M�dias', 'Blogs');
foreach ($rubriques as $rub_name) {
	$rub = Rubrique::CreateInMySQL($rub_name);
	$rub->loadFromMySQL();
	$rub->addParent($site);
	$rub->saveInMySQL();
}
$site->saveInMySQL();

?>