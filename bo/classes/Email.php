<?php

include_once 'classes/includes.php';

class Email extends Auteur {

	function __construct(Key $key) {
		parent::__construct($key);
	}
	
	////////////////////////
	// GETTERs et SETTERs //
	////////////////////////
	
	/**
	 * @return array
	 */
	function getIps() {
		return $this->findParentsOfClass('Ip');
	}
	
	/**
	 * @return array
	 */
	function getPseudos() {
		return $this->findChildrenOfClass('Pseudo');
	}

	//////////////////////////////////////////////////
	// M�thodes statiques de cr�ation dans la MySQL //
	//////////////////////////////////////////////////

	/**
	 * On cr�e l'enregistrement dans la MySQL et on renvoie le nouveau email
	 * IL FAUT AVOIR V�RIFI� QUE LE NOM DE EMAIL N'EXISTE PAS D�J�
	 * Les liens des Children et Parents ne sont pas cr��s
	 *
	 * @param string $nom
	 * @return Email
	 */
	static public function CreateInMySQL($nom = 'Email inconnu') {
		$nom = mysql_real_escape_string(stripslashes($nom));
		$time = time();
		$req = "INSERT INTO emails VALUES(DEFAULT, '$nom', $time, 0, 0, 0, 0, '', '')";
		if (mysql_query($req)) {
			return new Email(new Key('ema-'.mysql_insert_id()));
		} else {
			die("Impossible d'ins�rer le nouvel email, erreur MySQL : ".mysql_error());
		}
	}
	
}

?>