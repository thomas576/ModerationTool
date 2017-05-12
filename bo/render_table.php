<?php

include_once 'classes/includes.php';
include_once 'mysql_connect.inc.php';

if (session_id() == '') {
	session_start();
}

if (isset($_GET['parent'])) { 
	$key_parent_str = $_GET['parent'];
	$_SESSION['target'] = $key_parent_str;
} else { 
	$key_parent_str = 'root';
}

if ($key_parent_str == 'root') {
	
	$parent = null;
	$classe_fille = 'Site';
	
} else {

	$key_parent = new Key($key_parent_str);
	$class_name = $key_parent->getClassName();
	/* @var $parent Site */
	$parent = new $class_name($key_parent);
	
	if ($class_name == 'Site') {
		$classe_fille = 'Rubrique';
	} else if ($class_name == 'Rubrique') {
		$classe_fille = 'Article';
	} else if ($class_name == 'Article') {
		$classe_fille = 'Commentaire';
	}
}
$table_MySQL = Config::$array_tables_mysql_des_classes[$classe_fille];
if (isset($parent)) {
	$parent->loadFromMySQL();
	$children = $parent->getChildren();
} else {
	$children = array(new Site(new Key('sit-1')));
}


$debut = '<div class="pages_'.$table_MySQL.'">
		    <table class="table_'.$table_MySQL.'" width="100%">';

if ($classe_fille == 'Commentaire') {
	
	$head = '<tr class="table_'.$table_MySQL.'_head">
		    <th class="auteur" scope="col">Utilisateur (pseudo, email, ip)</th>
		    <th class="titre" scope="col">Titre</th>
		    <th class="texte" scope="col">Commentaire</th>
		    <th class="date" scope="col">Date</th>
		    <th class="mots" scope="col">Mots rep&eacute;r&eacute;s</th>
		    <th class="statut" scope="col">Statut</th>
		    <th class="remarque" scope="col">Remarque</th>
		  </tr>';
	
	$rows = '';
	foreach ($children as $child) {
		/* @var $child Commentaire */
		$child->loadFromMySQL();
		
		$rows .= $child->returnRowTableau();
	}
	
} else {
	
	$head = '<tr class="table_'.$table_MySQL.'_head">
		        <th class="nom" scope="col">Arborescence</th>
		        <th class="nbr_com" scope="col">Total commentaires</th>
		        <th class="separator" scope="col"></th>
		        <th class="N" scope="col">Nouveaux</th>
		        <th class="A" scope="col">En attente</th>
		        <th class="separator" scope="col"></th>
		        <th class="V" scope="col">Valid&eacute;s</th>
		        <th class="R" scope="col">Rejet&eacute;s</th>
		      </tr>';
	
	$rows = '';
	foreach ($children as $child) {
		/* @var $child Site */
		$child->loadFromMySQL();
		$NAVR = $child->getNAVR();
		$N = $NAVR['N']; $A = $NAVR['A']; $V = $NAVR['V']; $R = $NAVR['R'];
		$total = $N + $A + $V + $R;
		
		$id_tr = $child->getKey()->getKeyString();
		
		$lien_article = '';
		if ($classe_fille == 'Article') {
			$lien_article = ' <a href="ajouter_commentaire.php?article='.$id_tr.'" class="lien_article" target="_blank">(Voir l\'article)</a>';
		}
		
		$rows .= '<tr class="tr_'.$table_MySQL.'_haut">'."\n";
	      $rows .= '  <td class="nom"><a href="javascript:displayTable(\''.$id_tr.'\');">'.htmlentities($child->getNom()).'</a>'.$lien_article.'</td>'."\n";
	      $rows .= '  <td class="nbr_com">'.$total.'</td>'."\n";
	      $rows .= '  <td class="separator"></td>'."\n";
	      $rows .= '  <td class="N">'.$N.'</td>'."\n";
	      $rows .= '  <td class="A">'.$A.'</td>'."\n";
	      $rows .= '  <td class="separator"></td>'."\n";
	      $rows .= '  <td class="V">'.$V.'</td>'."\n";
	      $rows .= '  <td class="R">'.$R.'</td>'."\n";
	      $rows .= '</tr>'."\n";
	      $rows .= '<tr id="'.$id_tr.'" class="tr_'.$table_MySQL.'_bas" style="display:none;">';
	      $rows .= '<td class="td_table_rubriques" colspan="8">';
	      $rows .= '</td>';
	      $rows .= '</tr>'."\n";
	}
	
}

$fin = '</table>
  	</div>';

echo $debut."\n".$head."\n".$rows."\n".$fin;

mysql_close();

?>