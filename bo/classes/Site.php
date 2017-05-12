<?php

include_once 'classes/includes.php';

class Site extends Root {
	
	/**
	 * @var array
	 */
	protected $_NAVR;
	
	/**
	 * @param Key $key
	 */
	function __construct(Key $key) {
		parent::__construct($key);
		$this->setNAVR(array());
	}
	
	/**
	 * @param boolean $selected
	 * @return string
	 */
	function returnOptionLigneHTML($selected = false) {
		$sel = '';
		if ($selected) {
			$sel =  ' selected="selected"';
		}
		return '<option value="'.$this->getKey()->getKeyString().'"'.$sel.'>'.htmlentities($this->getNom()).'</option>';
	}
	
	/**
	 * @return string
	 */
	function returnSelectChildrenHTML() {
		$children = $this->getChildren();
		$select_children = '';
		$sel = true;
		foreach ($children as $child) {
			/* @var $child Site */
			$child->loadFromMySQL();
			$select_children .= $child->returnOptionLigneHTML($sel)."\n";
			$sel = false;
		}
		return $select_children;
	}
	
	/**
	 * @param Liste $nouvelleListe
	 * @param Liste $ancienneListe NULL si on a seulement ajouté une liste
	 */
	function updateNAVR(Liste $nouvelleListe, $ancienneListe = null) {
		$this->increaseNAVR($nouvelleListe);
		
		if (isset($ancienneListe)) {
			$this->increaseNAVR($ancienneListe, -1);
		}
		
	}
	
	/**
	 * @param Liste $liste
	 * @param int $value
	 */
	protected function increaseNAVR(Liste $liste, $value = 1) {
		$NAVR = $this->getNAVR();
		$key_liste_str = $liste->getKey()->getKeyString();
		if ($key_liste_str == N_COM) {
			$NAVR['N'] = $NAVR['N'] + $value;
		} else if ($key_liste_str == A_COM) {
			$NAVR['A'] = $NAVR['A'] + $value;
		} else if ($key_liste_str == V_COM) {
			$NAVR['V'] = $NAVR['V'] + $value;
		} else if ($key_liste_str == R_COM) {
			$NAVR['R'] = $NAVR['R'] + $value;
		}
		$this->setNAVR($NAVR);
	}
	
	/////////////////////////////
	// Chargement depuis MySQL //
	/////////////////////////////
	
	/**
	 * @param array $row
	 */
	protected function traiterRowMySQL($row) {
		parent::traiterRowMySQL($row);
		$this->getRowNAVR($row);
	}
	
	/**
	 * @param array $row
	 */
	function getRowNAVR($row) {
		$array_navr = array();
		$keys = array_keys(Config::$array_statuts_commentaires);
		foreach ($keys as $key) {
			$nombre = $row[$key];
			$nombre = $nombre + 1;
			$nombre--;
			$array_navr[$key] = $nombre;
		}
		$this->setNAVR($array_navr);
	}
	
	function saveInMySQL() {
		$table_MySQL = Config::$array_tables_mysql_des_classes[$this->getKey()->getClassName()];
		$NAVR = $this->getNAVR();
		$N = $NAVR['N'];
		$A = $NAVR['A'];
		$V = $NAVR['V'];
		$R = $NAVR['R'];
		$text_parents = $this->getTextParents();
		$text_children = $this->getTextChildren();
		$req = "UPDATE $table_MySQL SET N=$N, A=$A, V=$V, R=$R, parents='$text_parents', children='$text_children' WHERE id=".$this->getKey()->getNumber();
		if (!mysql_query($req)) {
			die("Impossible de sauvegarder dans MySQL, erreur MySQL : ".mysql_error());
		}
	}

	////////////////////////
	// GETTERs et SETTERs //
	////////////////////////
	
	/**
	 * @return array
	 */
	function getRubriques() {
		return array_values($this->getChildren());
	}
	
	/**
	 * @return array
	 */
	public function getNAVR() {
		return $this->_NAVR;
	}
	
	/**
	 * @param array $_NAVR
	 */
	public function setNAVR($_NAVR) {
		$this->_NAVR = $_NAVR;
	}

	//////////////////////////////////////////////////
	// Méthodes statiques de création dans la MySQL //
	//////////////////////////////////////////////////

	/**
	 * @param string $nom
	 * @return Site
	 */
	static public function CreateInMySQL($nom = 'Site sans nom') {
		$nom = mysql_real_escape_string(stripslashes($nom));
		$req = "INSERT INTO sites VALUES(DEFAULT, '$nom', 0, 0, 0, 0, '', '')";
		if (mysql_query($req)) {
			return new Site(new Key('sit-'.mysql_insert_id()));
		} else {
			die("Impossible d'insérer le nouveau site, erreur MySQL : ".mysql_error());
		}
	}
}

?>