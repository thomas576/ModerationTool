<?php

include_once 'classes/includes.php';

abstract class Root {
	
	/**
	 * @var Key
	 */
	protected $_key;
	/**
	 * @var string
	 */
	protected $_nom;
	/**
	 * @var array
	 */
	protected $_children;
	/**
	 * @var array
	 */
	protected $_parents;
	
	function __construct(Key $key) {
		$this->setKey($key);
		$this->setNom('');
		$this->setChildren(array());
		$this->setParents(array());
	}

	
	/////////////////////////////
	// Chargement depuis MySQL //
	/////////////////////////////
	
	function loadFromMySQL() {
		$table_MySQL = Config::$array_tables_mysql_des_classes[$this->getKey()->getClassName()];
		$req = "SELECT * FROM $table_MySQL WHERE id=".$this->getKey()->getNumber();
		$res = mysql_query($req);
		while ($row = mysql_fetch_assoc($res)) {
			$this->traiterRowMySQL($row);
		}
	}
	
	/**
	 * @param array $row
	 */
	protected function traiterRowMySQL($row) {
		$this->getRowNom($row);
		$this->getRowParents($row);
		$this->getRowChildren($row);
	}
	
	/**
	 * @param array $row
	 */
	function getRowNom($row) {
		$this->setNom($row['nom']);
	}

	/**
	 * @param array $row
	 */
	function getRowParents($row) {
		$text_parents = $row['parents'];
		$array_keys = explode(' ', $text_parents);
		foreach ($array_keys as $key_str) {
			if ($key_str != '') {
				$key = new Key($key_str);
				$this->addParent($this->returnObjectFromKey($key), false);
			}
		}
	}
	
	/**
	 * @param array $row
	 */
	function getRowChildren($row) {
		$text_children = $row['children'];
		$array_keys = explode(' ', $text_children);
		foreach ($array_keys as $key_str) {
			if ($key_str != '') {
				$key = new Key($key_str);
				$this->addChild($this->returnObjectFromKey($key), false);
			}
		}
	}
	
	/**
	 * @param Key $key
	 * @return Root
	 */
	function returnObjectFromKey(Key $key) {
		$class_name = $key->getClassName();
		return new $class_name($key);
	}
	
	function saveInMySQL() {
		$table_MySQL = Config::$array_tables_mysql_des_classes[$this->getKey()->getClassName()];
		$text_parents = $this->getTextParents();
		$text_children = $this->getTextChildren();
		$req = "UPDATE $table_MySQL SET parents='$text_parents', children='$text_children' WHERE id=".$this->getKey()->getNumber();
		if (!mysql_query($req)) {
			die("Impossible de sauvegarder dans MySQL, erreur MySQL : ".mysql_error());
		}
	}
	
	////////////////////////////////////////////////
	// Méthodes relatives aux Children et Parents //
	////////////////////////////////////////////////
	
	/**
	 * @param Root $child
	 * @param boolean $doReciproque
	 */
	function addChild(Root $child, $doReciproque = true) {
		$array_children = $this->getChildren();
		$array_children[$child->getKey()->getKeyString()] = $child;
		$this->setChildren($array_children);
		
		if ($doReciproque) {
			$child->addParent($this, false);
		}
	}
	
	/**
	 * @param Root $child
	 * @param boolean $doReciproque
	 */
	function deleteChild(Root $child, $doReciproque = true) {
		$array_children = $this->getChildren();
		if (array_key_exists($child->getKey()->getKeyString(), $array_children)) {
			unset($array_children[$child->getKey()->getKeyString()]);
		}
		$this->setChildren($array_children);
		
		if ($doReciproque) {
			$child->deleteParent($this, false);
		}
	}
	
	/**
	 * @return string
	 */
	function getTextChildren() {
		$text = '';
		$keys = array_keys($this->getChildren());
		$espace = '';
		foreach ($keys as $key_str) {
			$text .= $espace.$key_str;
			$espace = ' ';
		}
		return $text;
	}
	
	/**
	 * @param Root $parent
	 * @param boolean $doReciproque
	 */
	function addParent(Root $parent, $doReciproque = true) {
		$array_parents = $this->getParents();
		$array_parents[$parent->getKey()->getKeyString()] = $parent;
		$this->setParents($array_parents);
		
		if ($doReciproque) {
			$parent->addChild($this, false);
		}
	}
	
	/**
	 * @param Root $parent
	 * @param boolean $doReciproque
	 */
	function deleteParent(Root $parent, $doReciproque = true) {
		$array_parents = $this->getParents();
		if (array_key_exists($parent->getKey()->getKeyString(), $array_parents)) {
			unset($array_parents[$parent->getKey()->getKeyString()]);
		}
		$this->setParents($array_parents);
		
		if ($doReciproque) {
			$parent->deleteChild($this, false);
		}
	}
	
	/**
	 * @return string
	 */
	function getTextParents() {
		$text = '';
		$keys = array_keys($this->getParents());
		$espace = '';
		foreach ($keys as $key_str) {
			$text .= $espace.$key_str;
			$espace = ' ';
		}
		return $text;
	}
	
	////////////////////////
	// GETTERs et SETTERs //
	////////////////////////
	
	/**
	 * Il ne doit y avoir qu'un seul parent appartenant à la classe demandée
	 *
	 * @param string $classname
	 * @return Root
	 */
	protected function findTheOnlyParentOfClass($classname) {
		$parents = $this->getParents();
		$keys_parents = array_keys($parents);
		
		$code = array_search($classname, Config::$array_key_classes);
		
		foreach ($keys_parents as $key) {
			if (strpos($key, $code) !== false) {
				return $parents[$key];
			}
		}
		die("Aucun '$classname' n'a été trouvé dans les parents pour cet élément !");
	}
	
	/**
	 * Il ne doit y avoir qu'un seul child appartenant à la classe demandée
	 *
	 * @param string $classname
	 * @return Root
	 */
	protected function findTheOnlyChildOfClass($classname) {
		$children = $this->getChildren();
		$keys_children = array_keys($children);
		
		$code = array_search($classname, Config::$array_key_classes);
		
		foreach ($keys_children as $key) {
			if (strpos($key, $code) !== false) {
				return $children[$key];
			}
		}
		die("Aucun '$classname' n'a été trouvé dans les children pour cet élément !");
	}
	
	/**
	 * Retourne un tableau de tous les parents appartenant à la classe demandée
	 *
	 * @param string $classname
	 * @return array
	 */
	protected function findParentsOfClass($classname) {
		$parents = $this->getParents();
		$keys_parents = array_keys($parents);
		
		$code = array_search($classname, Config::$array_key_classes);
		
		$array_return = array();
		foreach ($keys_parents as $key) {
			if (strpos($key, $code) !== false) {
				$array_return[] = $parents[$key];
			}
		}
		return $array_return;
	}
	
	/**
	 * Retourne un tableau de tous les children appartenant à la classe demandée
	 *
	 * @param string $classname
	 * @return array
	 */
	protected function findChildrenOfClass($classname) {
		$children = $this->getChildren();
		$keys_children = array_keys($children);
		
		$code = array_search($classname, Config::$array_key_classes);
		
		$array_return = array();
		foreach ($keys_children as $key) {
			if (strpos($key, $code) !== false) {
				$array_return[] = $children[$key];
			}
		}
		return $array_return;
	}
	
	/**
	 * @return Key
	 */
	public function getKey() {
		return $this->_key;
	}
	
	/**
	 * @param Key $_key
	 */
	public function setKey(Key $_key) {
		if (isset($_key) && $_key instanceof Key) {
			$this->_key = $_key;
		} else {
			die("La Clé donnée n'est pas un objet de type Key non NULL !");
		}
	}
	
	/**
	 * @return array
	 */
	public function getChildren() {
		return $this->_children;
	}
	
	/**
	 * @param array $_children
	 */
	public function setChildren($_children) {
		$this->_children = $_children;
	}
	
	/**
	 * @return array
	 */
	public function getParents() {
		return $this->_parents;
	}
	
	/**
	 * @param array $_parents
	 */
	public function setParents($_parents) {
		$this->_parents = $_parents;
	}
	
	/**
	 * @return string
	 */
	public function getNom() {
		return $this->_nom;
	}
	
	/**
	 * @param string $_nom
	 */
	public function setNom($_nom) {
		$this->_nom = $_nom;
	}
	
	//////////////////////////////////////////////////
	// Méthodes statiques de création dans la MySQL //
	//////////////////////////////////////////////////
	
	/**
	 * Retourne l'Objet si son nom existe déjà, sinon retourne NULL
	 *
	 * @param string $nom
	 * @param string $classname
	 * @return Root
	 */
	static public function returnIfNomAlreadyInMySQL($nom, $classname) {
		$nom = mysql_real_escape_string(stripslashes($nom));
		$table_MySQL = Config::$array_tables_mysql_des_classes[$classname];
		$req = "SELECT id FROM $table_MySQL WHERE nom='$nom'";
		$res = mysql_query($req);
		if (mysql_num_rows($res) > 0) {
			$row = mysql_fetch_assoc($res);
			$code = array_search($classname, Config::$array_key_classes);
			return new $classname(new Key($code.'-'.$row['id']));
		} else {
			return null;
		}
	}
	
}

?>