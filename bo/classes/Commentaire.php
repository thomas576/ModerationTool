<?php

include_once 'classes/includes.php';

class Commentaire extends Article {
	
	/**
	 * @var string
	 */
	protected $_remarque;
	/**
	 * @var array
	 */
	protected $_LRLN;
	
	function __construct(Key $key) {
		parent::__construct ($key);
		$this->setRemarque('');
		$this->setLRLN(array());
	}
	
	/**
	 * @return string
	 */
	function returnRowInSimpleDisplayHTML() {
		$pseudo = $this->getPseudo();
		$pseudo->loadFromMySQL();
		
		$html_com = '<tr>'."\n";
		$html_com .= '<td class="auteur_com">'.htmlentities($pseudo->getNom()).'</td>'."\n";
		$html_com .= '<td class="titre_com">'.htmlentities($this->getNom()).'</td>'."\n";
		$html_com .= '<td class="texte_com">'.htmlentities($this->getTexte()).'</td>'."\n";
		$html_com .= '<td class="alerter_com"><a class="alerter_com" href="alerter.php?com='.$this->getKey()->getKeyString().'">Alerter</a></td>'."\n";
		$html_com .= '</tr>'."\n";
		
		return $html_com;
	}
	
	/**
	 * @param string $from
	 * @return string
	 */
	function returnRowTableau($from = 'render_tables') {
		$row = '';
		
		$pseudo = $this->getPseudo();
		$pseudo->loadFromMySQL();
		
		$email = $this->getEmail();
		$email->loadFromMySQL();
		
		$ip = $this->getIp();
		$ip->loadFromMySQL();
		
		$LRLN = $this->getLRLN();
		
		$nom_liste = $this->returnNameOfListe();
		$images = array();
		
		if ($from == 'render_tables') {
			$class = 'tr_commentaires_haut';
			if (isset($_GET['page'])) {
				$page = $_GET['page'];
			} else {
				$page = 0;
			}
			$article = $this->getArticle();
			$url_return = urlencode('tableau_global.php?target='.$article->getKey()->getKeyString().'&page='.$page);
		} else if ($from == 'infos_auteur') {
			$class = 'tr_commentaire';
			$url_return = urlencode($_SERVER['REQUEST_URI']);
		} else {
			$class = '';
			$url_return = urlencode($_SERVER['REQUEST_URI']);
		}
		
		foreach (Config::$array_statuts_commentaires as $lettre => $detail) {
			if (stripos($nom_liste, $lettre) !== false) {
				$images[$lettre] = '<img src="images/'.$lettre.'.png" alt="'.htmlentities($detail).'" width="20" height="20" border="0">';
			} else {
				$images[$lettre] = '<a href="change_liste.php?key='.$this->getKey()->getKeyString().'&setliste='.$lettre.'_COM&return='.$url_return.'"><img src="images/'.$lettre.'-hidden.png" onMouseOut="this.src=\'images/'.$lettre.'-hidden.png\'" onMouseOver="this.src=\'images/'.$lettre.'.png\'" alt="Changer le statut en : '.htmlentities($detail).' ?" width="20" height="20" border="0"></a>';
			}
		}
		
		$LR = $LRLN['LR']; 
		$LN = $LRLN['LN'];
		$span_LR_MOT = '<span>'; if ($LR > 0) { $span_LR_MOT = '<span class="LR_MOT">'; }
		$span_LN_MOT = '<span>'; if ($LN > 0) { $span_LN_MOT = '<span class="LN_MOT">'; }
		
		$row .= '<tr class="'.$class.'">'."\n";
		if ($from != 'infos_auteur') {
			$row .= '  <td class="auteur">'.$pseudo->returnNomInColor().' :: '.$email->returnNomInColor().' :: '.$ip->returnNomInColor().'</td>'."\n";
		}
		$row .= '  <td class="titre"><a href="verifier_commentaire.php?com='.$this->getKey()->getKeyString().'">'.htmlentities($this->getNom()).'</a></td>'."\n";
		$row .= '  <td class="texte">'.htmlentities($this->getShortTexte(80)).'</td>'."\n";
		$row .= '  <td class="date">'.date('d/m/y H\hi', $this->getTime()).'</td>'."\n";
		
		$row .= '  <td class="mots">'.$span_LR_MOT.$LR.'</span> '.$span_LN_MOT.$LN.'</span></td>'."\n";
		$row .= '  <td class="statut">'.$images['N'].$images['A'].$images['V'].$images['R'].'</td>'."\n";
		$row .= '  <td class="remarque">'.htmlentities($this->getRemarque()).'</td>'."\n";
		$row .= '</tr>'."\n";
		
		return $row;
	}
	
	/**
	 * @return string
	 */
	function returnNameOfListe() {
		return Config::$array_keys_noms_liste[$this->getListe()->getKey()->getKeyString()];
	}
	
	function updateNAVR(Liste $nouvelleListe, $ancienneListe) {
		
	}

	protected function increaseNAVR(Liste $liste, $value = 1) {
		
	}
	
	/**
	 * @param Mot $mot LE MOT DOIT DÉJÀ ÊTRE DANS LES PARENTS/CHILDREN DU COMMENTAIRE
	 * @param Liste $ancienneListe
	 * @param Liste $nouvelleListe NULL si on veut supprimer le mot
	 */
	function updateLRLN(Mot $mot, $ancienneListe, $nouvelleListe = null) {
		$out = array();
		if ($nbr_matches = preg_match_all('#\b'.preg_quote($mot->getNom()).'\b#i', $this->getNom().' // '.$this->getTexte(), $out)) {
			if (isset($nouvelleListe)) {
				$this->increaseLRLN($nouvelleListe, $nbr_matches);
			}
			$this->increaseLRLN($ancienneListe, -1 * $nbr_matches);
		}
	}
	
	/**
	 * @param Liste $liste
	 * @param int $value
	 */
	protected function increaseLRLN(Liste $liste, $value = 1) {
		$LRLN = $this->getLRLN();
		$key_liste_str = $liste->getKey()->getKeyString();
		if ($key_liste_str == LR_MOT) {
			$LRLN['LR'] = $LRLN['LR'] + $value;
		} else if ($key_liste_str == LN_MOT) {
			$LRLN['LN'] = $LRLN['LN'] + $value;
		}
		$this->setLRLN($LRLN);
	}
	
	function checkForWordsListedAndCalculateLRLN() {
		$this->setLRLN(array('LR' => 0, 'LN' => 0));
		$mots = Mot::getFastArrayOfMots();
		foreach ($mots as $mot) {
			/* @var $mot Mot */
			$out = array();
			if ($nbr_matches = preg_match_all('#\b'.preg_quote($mot->getNom()).'\b#i', $this->getNom().' // '.$this->getTexte(), $out)) {
				$mot->loadFromMySQL();
				$this->increaseLRLN($mot->getListe(), $nbr_matches);
				$mot->addChild($this);
				$mot->addParent($this);
				$mot->saveInMySQL();
			}
		}
	}
	
	function checkIfWordIsInCommentaireAndUpdateLRLN(Mot $mot) {
		$out = array();
		if ($nbr_matches = preg_match_all('#\b'.preg_quote($mot->getNom()).'\b#i', $this->getNom().' // '.$this->getTexte(), $out)) {
			$this->increaseLRLN($mot->getListe(), $nbr_matches);
			$mot->addChild($this);
			$mot->addParent($this);
		}
	}
	
	/**
	 * @param string $texte
	 * @return string
	 */
	function colorWithSpan($texte) {
		$mots = $this->getMots();
		$html = $texte;
		foreach ($mots as $mot) {
			/* @var $mot Mot */
			$mot->loadFromMySQL();
			$html = preg_replace('#\b'.preg_quote($mot->getNom()).'\b#i', '<span class="'.$mot->returnNameOfListe().'">${0}</span>', $html);
		}
		$html = htmlentities($html);
		return preg_replace('#&lt;(/?)span(.*?)&gt;#e', "html_entity_decode('\\0')", $html);
	}
	
	/**
	 * @return string
	 */
	function returnTitreWithColorSpan() {
		return $this->colorWithSpan($this->getNom());
	}
	
	/**
	 * @return string
	 */
	function returnTexteWithColorSpan() {
		return $this->colorWithSpan($this->getTexte());
	}
	
	/////////////////////////////
	// Chargement depuis MySQL //
	/////////////////////////////
	
	/**
	 * @param array $row
	 */
	protected function traiterRowMySQL($row) {
		parent::traiterRowMySQL($row);
		$this->getRowRemarque($row);
		$this->getRowLRLN($row);
	}
	
	/**
	 * @param array $row
	 */
	function getRowRemarque($row) {
		$this->setRemarque($row['remarque']);
	}
	
	/**
	 * @param array $row
	 */
	function getRowLRLN($row) {
		$array_lrln = array();
		$keys = array('LR', 'LN');
		foreach ($keys as $key) {
			$nombre = $row[$key];
			$nombre = $nombre + 1;
			$nombre--;
			$array_lrln[$key] = $nombre;
		}
		$this->setLRLN($array_lrln);
	}
	
	/**
	 * @param array $row
	 */
	function getRowNAVR($row) {
		// Pas de NAVR
	}
	
	function saveInMySQL() {
		$table_MySQL = Config::$array_tables_mysql_des_classes[$this->getKey()->getClassName()];
		$remarque = mysql_real_escape_string(stripslashes($this->getRemarque()));
		$LRLN = $this->getLRLN();
		$LR = $LRLN['LR'];
		$LN = $LRLN['LN'];
		$text_parents = $this->getTextParents();
		$text_children = $this->getTextChildren();
		$req = "UPDATE $table_MySQL SET remarque='$remarque', LR=$LR, LN=$LN, parents='$text_parents', children='$text_children' WHERE id=".$this->getKey()->getNumber();
		if (!mysql_query($req)) {
			die("Impossible de sauvegarder dans MySQL, erreur MySQL : ".mysql_error());
		}
	}

	////////////////////////
	// GETTERs et SETTERs //
	////////////////////////
	
	/**
	 * @return Article
	 */
	function getArticle() {
		return $this->findTheOnlyParentOfClass('Article');
	}
	
	/**
	 * @return Liste
	 */
	function getListe() {
		return $this->findTheOnlyParentOfClass('Liste');
	}
	
	/**
	 * @return Pseudo
	 */
	function getPseudo() {
		return $this->findTheOnlyParentOfClass('Pseudo');
	}
	
	/**
	 * @return Email
	 */
	function getEmail() {
		return $this->findTheOnlyParentOfClass('Email');
	}
	
	/**
	 * @return Ip
	 */
	function getIp() {
		return $this->findTheOnlyParentOfClass('Ip');
	}
	
	/**
	 * @return array
	 */
	function getMots() {
		return array_values($this->getChildren());
	}
	
	/**
	 * @return array
	 */
	public function getLRLN() {
		return $this->_LRLN;
	}
	
	/**
	 * @param array $_LRLN
	 */
	public function setLRLN($_LRLN) {
		$this->_LRLN = $_LRLN;
	}
	
	/**
	 * @return string
	 */
	public function getRemarque() {
		return $this->_remarque;
	}
	
	/**
	 * @param string $_remarque
	 */
	public function setRemarque($_remarque) {
		$this->_remarque = $_remarque;
	}
	
	function getShortTexte($length) {
		$texte = $this->getTexte();
		if (strlen($texte) > $length) {
			$texte = substr($texte, 0, $length-3);
			$texte .= '...';
		}
		return $texte;
	}

	//////////////////////////////////////////////////
	// Méthodes statiques de création dans la MySQL //
	//////////////////////////////////////////////////

	/**
	 * @param string $nom
	 * @param string $texte
	 * @return Article
	 */
	static public function CreateInMySQL($nom = 'Commentaire sans nom', $texte = 'Commentaire vide') {
		$nom = mysql_real_escape_string(stripslashes($nom));
		$texte = mysql_real_escape_string(stripslashes($texte));
		$time = time();
		$req = "INSERT INTO commentaires VALUES(DEFAULT, '$nom', '$texte', '', $time, 0, 0, '', '')";
		if (mysql_query($req)) {
			return new Commentaire(new Key('com-'.mysql_insert_id()));
		} else {
			die("Impossible d'insérer le nouveau commentaire, erreur MySQL : ".mysql_error());
		}
	}
}

?>