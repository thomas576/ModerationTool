<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

/*$texte = 'ema-1254 ipa-78 pse-78999 lis-1';
echo preg_replace('#\blis\-(\d+)\b#', 'lis-2', $texte, 1);
echo time()."\n";

$key = new Key('lis-2121211');
$key->setClassName('Article');
echo $key->getClassName()."\n";
echo $key->getNumber()."\n";
echo $key->getKeyString()."\n";
*/
//print_r(explode(' ', ''));

/*$rubrique = Rubrique::CreateInMySQL('Économie');
$rubrique->loadFromMySQL();

$article1 = Article::CreateInMySQL('Tour de France : Le vainqueur se dopait !', "Encoer une fois c'était sûr !");
$article1->loadFromMySQL();
$article1->addParent($rubrique);
$article1->saveInMySQL();
$rubrique->saveInMySQL();*/
echo preg_replace('#&lt;(/?)span(.*?)&gt;#e', "html_entity_decode('\\0')", 'j&eacute; su&iuml;s une mouche &agrave; &lt;span class=&quot;LR_MOT&quot;&gt;m&egrave;rde&lt;/span&gt;.');
?>