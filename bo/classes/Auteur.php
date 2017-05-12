<?php

include_once 'classes/includes.php';

abstract class Auteur extends Root {
	
	/**
	 * @var array
	 */
	protected $_NAVR;
	/** 
	 * @var int
	 */
	protected $_time;
	
	function __construct(Key $key) {
		parent::__construct($key);
		$this->setNAVR(array());
		$this->setTime(0);
	}

	/**
	 * @return string
	 */
	function returnNomInColor($withLink = true) {
		if ($withLink) {
			$a1 = '<a href="infos_auteur.php?auteur='.$this->getKey()->getKeyString().'" target="_self">';
			$a2 = '</a>';
		} else {
			$a1 = '';
			$a2 = '';
		}
		return '<span class="'.$this->returnNameOfListe().'">'.$a1.htmlentities($this->getNom()).$a2.'</span>';
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
		
		$class_name = $this->getKey()->getClassName();
		if ($class_name == 'Pseudo') {
			$suffixe = '_PSE';
		} else if ($class_name == 'Email') {
			$suffixe = '_EMA';
		} else {
			$suffixe = '_IPA';
		}
		
		$NAVR = $this->getNAVR();
		$N = $NAVR['N']; $A = $NAVR['A']; $V = $NAVR['V']; $R = $NAVR['R'];
		$total = $N + $A + $V + $R;
		
		$change_liste = '';
		foreach (Config::$array_listes_mots as $prefixe => $detail) {
			if (stripos($this->returnNameOfListe(), $prefixe) === false) {
				$change_liste .= '<div class="bouton_'.$prefixe.'"><a href="change_liste.php?key='.$this->getKey()->getKeyString().'&setliste='.$prefixe.$suffixe.'&return='.urlencode($_SERVER['REQUEST_URI']).'"><img src="images/'.$prefixe.'-hidden.png" onMouseOut="this.src=\'images/'.$prefixe.'-hidden.png\'" onMouseOver="this.src=\'images/'.$prefixe.'.png\'" alt="Mettre en '.htmlentities($detail).' ?" width="25" height="25" border="0"></a></div>';
			}
		}
		
		$row .= '<tr>'."\n";
		$row .= '  <td class="'.strtolower($class_name).'">'.$this->returnNomInColor().'</td>'."\n";
		$row .= '  <td class="nbr_com">'.$total.'</td>'."\n";
		$row .= '  <td class="separator"></span></td>'."\n";
		$row .= '  <td class="N">'.$N.'</td>'."\n";
		$row .= '  <td class="A">'.$A.'</td>'."\n";
		$row .= '  <td class="separator"></span></td>'."\n";
		$row .= '  <td class="V">'.$V.'</td>'."\n";
		$row .= '  <td class="R">'.$R.'</td>'."\n";
		$row .= '  <td class="change_liste">'.$change_liste.'</td>'."\n";
		$row .= '</tr>'."\n";
		
		return $row;
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
		$this->getRowTime($row);
	}
	
	/**
	 * @param array $row
	 */
	function getRowTime($row) {
		$this->setTime($row['time']);
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
	 * @return Liste
	 */
	function getListe() {
		$parents = $this->getParents();
		$keys_parents = array_keys($parents);
		
		foreach ($keys_parents as $key) {
			if (strpos($key, 'lis') !== false) {
				return $parents[$key];
			}
		}
		die("Aucune Liste n'a été trouvée pour ce pseudo/email/ip !");
	}
	
	/**
	 * @return array
	 */
	function getCommentaires() {
		return $this->findChildrenOfClass('Commentaire');
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
	
	/**
	 * @return int
	 */
	public function getTime() {
		return $this->_time;
	}
	
	/**
	 * @param int $_time
	 */
	public function setTime($_time) {
		$this->_time = $_time;
	}
	
	static function sortByNom(Auteur $a, Auteur $b) {
		return strnatcasecmp($a->getNom(), $b->getNom());
	}
	
}

?>