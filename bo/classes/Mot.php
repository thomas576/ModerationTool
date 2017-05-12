<?php

include_once 'classes/includes.php';

class Mot extends Root {
	
	function __construct(Key $key) {
		parent::__construct($key);
	}
	
	/**
	 * @return string
	 */
	function returnNomInColor() {
		return '<span class="'.$this->returnNameOfListe().'">'.htmlentities($this->getNom()).'</span>';
	}
	
	/**
	 * @return string
	 */
	function returnNameOfListe() {
		return Config::$array_keys_noms_liste[$this->getListe()->getKey()->getKeyString()];
	}
	
	/**
	 * @return string
	 */
	function returnRowInListe() {
		$row = '';
		
		$url_return = urlencode($_SERVER['REQUEST_URI']);
		
		if ($this->returnNameOfListe() == 'LR_MOT') {
			$change_liste = '<div class="bouton_LN_MOT"><a href="change_liste.php?key='.$this->getKey()->getKeyString().'&setliste=LN_MOT&return='.$url_return.'"><img src="images/LN-hidden.png" onMouseOut="this.src=\'images/LN-hidden.png\'" onMouseOver="this.src=\'images/LN.png\'" alt="Mettre en liste noire des mots ?" width="25" height="25" border="0"></a></div>';
		} else {
			$change_liste = '<div class="bouton_LR_MOT"><a href="change_liste.php?key='.$this->getKey()->getKeyString().'&setliste=LR_MOT&return='.$url_return.'"><img src="images/LR-hidden.png" onMouseOut="this.src=\'images/LR-hidden.png\'" onMouseOver="this.src=\'images/LR.png\'" alt="Mettre en liste rouge des mots ?" width="25" height="25" border="0"></a></div>';
		}
		
		$nbr_coms = count($this->getCommentaires());
		$phrase_coms = $nbr_coms.' commentaire';
		if ($nbr_coms > 1) {
			$phrase_coms .= 's';
		}
		
		$row .= '<tr>'."\n";
		$row .= '  <td class="mot">'.$this->returnNomInColor().'</td>'."\n";
		$row .= '  <td class="nbr_com_mot">'.$phrase_coms.'</td>'."\n";
		$row .= '  <td class="change_liste">'.$change_liste.'</td>'."\n";
		$row .= '  <td class="suppr"><a href="change_liste.php?key='.$this->getKey()->getKeyString().'&setliste=OK_MOT&return='.$url_return.'"><img src="images/suppr-hidden.png" onMouseOut="this.src=\'images/suppr-hidden.png\'" onMouseOver="this.src=\'images/suppr.png\'" alt="Supprimer le mot ?" width="20" height="20" border="0"></a></td>'."\n";
		$row .= '</tr>'."\n";
		
		return $row;
	}
	
	////////////////////////
	// GETTERs et SETTERs //
	////////////////////////
	
	/**
	 * @return Liste
	 */
	function getListe() {
		return $this->findTheOnlyParentOfClass('Liste');
	}
	
	/**
	 * @return array
	 */
	function getCommentaires() {
		return array_values($this->getChildren());
	}
	
	//////////////////////////////////////////////////
	// Méthodes statiques de création dans la MySQL //
	//////////////////////////////////////////////////

	/**
	 * On crée l'enregistrement dans la MySQL et on renvoie le nouveau mot
	 * IL FAUT AVOIR VÉRIFIÉ QUE LE NOM DE MOT N'EXISTE PAS DÉJÀ
	 * Les liens des Children et Parents ne sont pas créés
	 *
	 * @param string $nom
	 * @return Mot
	 */
	static public function CreateInMySQL($nom) {
		$nom = mysql_real_escape_string(stripslashes($nom));
		$req = "INSERT INTO mots VALUES(DEFAULT, '$nom', '', '')";
		if (mysql_query($req)) {
			return new Mot(new Key('mot-'.mysql_insert_id()));
		} else {
			die("Impossible d'insérer le nouveau mot, erreur MySQL : ".mysql_error());
		}
	}
	
	/**
	 * Recherche rapidement tous les mots et ne charge que leur key et leur nom
	 * 
	 * @return array
	 */
	static public function getFastArrayOfMots() {
		$array_mots = array();
		$table_MySQL = Config::$array_tables_mysql_des_classes['Mot'];
		$req = "SELECT id,nom FROM $table_MySQL";
		$res = mysql_query($req);
		while ($row = mysql_fetch_assoc($res)) {
			$mot = new Mot(new Key('mot-'.$row['id']));
			$mot->setNom($row['nom']);
			$array_mots[] = $mot;
		}
		return $array_mots;
	}
	
	static function sortByNom(Mot $a, Mot $b) {
		return strnatcasecmp($a->getNom(), $b->getNom());
	}
	
	static function deleteMotFromMySQL(Mot $mot) {
		$id = $mot->getKey()->getNumber();
		$req = "DELETE FROM mots WHERE id=$id";
		if (!mysql_query($req)) {
			die("Impossible de supprimer le mot, erreur MySQL : ".mysql_error());
		}
	}
	
}

?>