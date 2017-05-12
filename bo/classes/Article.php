<?php

include_once 'classes/includes.php';

class Article extends Rubrique {
	
	/**
	 * @var string
	 */
	protected $_texte;
	
	function __construct(Key $key) {
		parent::__construct($key);
		$this->setTexte('');
	}
	
	/////////////////////////////
	// Chargement depuis MySQL //
	/////////////////////////////
	
	/**
	 * @param array $row
	 */
	protected function traiterRowMySQL($row) {
		parent::traiterRowMySQL($row);
		$this->getRowTexte($row);
	}
	
	/**
	 * @param array $row
	 */
	function getRowTexte($row) {
		$this->setTexte($row['texte']);
	}

	////////////////////////
	// GETTERs et SETTERs //
	////////////////////////
	
	/**
	 * @return Rubrique
	 */
	function getRubrique() {
		$faux_array = array_values($this->getParents());
		$rubrique = $faux_array[0];
		if ($rubrique instanceof Rubrique) {
			return $rubrique;
		} else {
			die("Le parent de l'article n'est pas une Rubrique !");
		}
	}
	
	/**
	 * @return array
	 */
	function getCommentaires() {
		return array_values($this->getChildren());
	}
	
	/**
	 * @return string
	 */
	public function getTexte() {
		return $this->_texte;
	}
	
	/**
	 * @param string $_texte
	 */
	public function setTexte($_texte) {
		$this->_texte = $_texte;
	}

	//////////////////////////////////////////////////
	// Méthodes statiques de création dans la MySQL //
	//////////////////////////////////////////////////

	/**
	 * @param string $nom
	 * @param string $texte
	 * @return Article
	 */
	static public function CreateInMySQL($nom = 'Article sans nom', $texte = 'Article vide') {
		$nom = mysql_real_escape_string(stripslashes($nom));
		$texte = mysql_real_escape_string(stripslashes($texte));
		$time = time();
		$req = "INSERT INTO articles VALUES(DEFAULT, '$nom', '$texte', $time, 0, 0, 0, 0, '', '')";
		if (mysql_query($req)) {
			return new Article(new Key('art-'.mysql_insert_id()));
		} else {
			die("Impossible d'insérer le nouvel article, erreur MySQL : ".mysql_error());
		}
	}
}

?>