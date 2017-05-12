<?php

include_once 'classes/includes.php';

class Key {
	
	/**
	 * @var string
	 */
	private $_key_str;
	/**
	 * @var string
	 */
	private $_class_name;
	/**
	 * @var int
	 */
	private $_number;
	
	function __construct($key_str) {
		$this->setKeyString($key_str);
	}
	
	private function decomposeKeyString() {
		list($class_id, $num) = explode('-', $this->_key_str);
		$class_id = $class_id.'';
		$class_name = Config::$array_key_classes[$class_id];
		$num = $num + 1;
		$num--;
		$this->setClassName($class_name);
		$this->setNumber($num);
	}
	
	private function recomposeKeyString() {
		$class_id = array_search($this->_class_name, Config::$array_key_classes);
		$this->setKeyString($class_id.'-'.$this->_number);
	}
	
	/**
	 * @return string
	 */
	public function getKeyString() {
		$this->recomposeKeyString();
		return $this->_key_str;
	}
	
	/**
	 * @param string $_key_str
	 */
	public function setKeyString($_key_str) {
		if (preg_match('#\A\w{3}\-\d+\z#', $_key_str)) {
			$this->_key_str = $_key_str;
			$this->decomposeKeyString();
		} else {
			die("La Clé n'est pas correcte : ".$_key_str." ! \n");
		}
		
	}
	
	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->_class_name;
	}
	
	/**
	 * @param string $_class_name
	 */
	public function setClassName($_class_name) {
		$this->_class_name = $_class_name;
	}
	
	/**
	 * @return int
	 */
	public function getNumber() {
		return $this->_number;
	}
	
	/**
	 * @param int $_number
	 */
	public function setNumber($_number) {
		$this->_number = $_number;
	}

}

?>