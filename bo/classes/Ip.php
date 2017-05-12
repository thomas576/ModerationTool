<?php

include_once 'classes/includes.php';

class Ip extends Auteur {

	function __construct(Key $key) {
		parent::__construct($key);
	}
	
	////////////////////////
	// GETTERs et SETTERs //
	////////////////////////
	
	/**
	 * @return array
	 */
	function getPseudos() {
		return $this->findParentsOfClass('Pseudo');
	}
	
	/**
	 * @return array
	 */
	function getEmails() {
		return $this->findChildrenOfClass('Email');
	}

	//////////////////////////////////////////////////
	// M�thodes statiques de cr�ation dans la MySQL //
	//////////////////////////////////////////////////

	/**
	 * On cr�e l'enregistrement dans la MySQL et on renvoie la nouvelle ip
	 * IL FAUT AVOIR V�RIFI� QUE LE NOM DE IP N'EXISTE PAS D�J�
	 * Les liens des Children et Parents ne sont pas cr��s
	 *
	 * @param string $nom
	 * @return Ip
	 */
	static public function CreateInMySQL($nom = 'Ip inconnue') {
		$nom = mysql_real_escape_string(stripslashes($nom));
		$time = time();
		$req = "INSERT INTO ips VALUES(DEFAULT, '$nom', $time, 0, 0, 0, 0, '', '')";
		if (mysql_query($req)) {
			return new Ip(new Key('ipa-'.mysql_insert_id()));
		} else {
			die("Impossible d'ins�rer la nouvelle ip, erreur MySQL : ".mysql_error());
		}
	}
	
}

?>