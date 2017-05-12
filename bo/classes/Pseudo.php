<?php

include_once 'classes/includes.php';

class Pseudo extends Auteur {
	
	function __construct(Key $key) {
		parent::__construct($key);
	}
	
	////////////////////////
	// GETTERs et SETTERs //
	////////////////////////
	
	/**
	 * @return array
	 */
	function getEmails() {
		return $this->findParentsOfClass('Email');
	}
	
	/**
	 * @return array
	 */
	function getIps() {
		return $this->findChildrenOfClass('Ip');
	}

	//////////////////////////////////////////////////
	// M�thodes statiques de cr�ation dans la MySQL //
	//////////////////////////////////////////////////

	/**
	 * On cr�e l'enregistrement dans la MySQL et on renvoie le nouveau pseudo
	 * IL FAUT AVOIR V�RIFI� QUE LE NOM DE PSEUDO N'EXISTE PAS D�J�
	 * Les liens des Children et Parents ne sont pas cr��s
	 *
	 * @param string $nom
	 * @return Pseudo
	 */
	static public function CreateInMySQL($nom = 'Pseudo inconnu') {
		$nom = mysql_real_escape_string(stripslashes($nom));
		$time = time();
		$req = "INSERT INTO pseudos VALUES(DEFAULT, '$nom', $time, 0, 0, 0, 0, '', '')";
		if (mysql_query($req)) {
			return new Pseudo(new Key('pse-'.mysql_insert_id()));
		} else {
			die("Impossible d'ins�rer le nouveau pseudo, erreur MySQL : ".mysql_error());
		}
	}
}

?>