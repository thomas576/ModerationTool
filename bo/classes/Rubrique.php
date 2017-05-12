<?php

include_once 'classes/includes.php';

class Rubrique extends Site {
	
	/**
	 * @var int
	 */
	protected $_time;
	
	function __construct(Key $key) {
		parent::__construct($key);
		$this->setTime(0);
	}
	
	/////////////////////////////
	// Chargement depuis MySQL //
	/////////////////////////////
	
	/**
	 * @param array $row
	 */
	protected function traiterRowMySQL($row) {
		parent::traiterRowMySQL($row);
		$this->getRowTime($row);
	}
	
	/**
	 * @param array $row
	 */
	function getRowTime($row) {
		$this->setTime($row['time']);
	}

	////////////////////////
	// GETTERs et SETTERs //
	////////////////////////
	
	/**
	 * @return Site
	 */
	function getSite() {
		$faux_array = array_values($this->getParents());
		$site = $faux_array[0];
		if ($site instanceof Site) {
			return $site;
		} else {
			die("Le parent de la rubrique n'est pas un Site !");
		}
	}
	
	/**
	 * @return array
	 */
	function getArticles() {
		return array_values($this->getChildren());
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
	
	//////////////////////////////////////////////////
	// Méthodes statiques de création dans la MySQL //
	//////////////////////////////////////////////////

	/**
	 * @param string $nom
	 * @return Rubrique
	 */
	static public function CreateInMySQL($nom = 'Rubrique sans nom') {
		$nom = mysql_real_escape_string(stripslashes($nom));
		$time = time();
		$req = "INSERT INTO rubriques VALUES(DEFAULT, '$nom', $time, 0, 0, 0, 0, '', '')";
		if (mysql_query($req)) {
			return new Rubrique(new Key('rub-'.mysql_insert_id()));
		} else {
			die("Impossible d'insérer la nouvelle rubrique, erreur MySQL : ".mysql_error());
		}
	}
	
}

?>