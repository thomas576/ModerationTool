<?php

include_once 'classes/includes.php';

class Liste extends Root {
	
	function __construct(Key $key) {
		parent::__construct($key);
	}
	
	////////////////////////
	// GETTERs et SETTERs //
	////////////////////////
	
	/**
	 * @return array
	 */
	function getMots() {
		return array_values($this->getChildren());
	}
	
	/**
	 * @return array
	 */
	function getCommentaires() {
		return array_values($this->getChildren());
	}
	
}

?>