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
	// Méthodes statiques de création dans la MySQL //
	//////////////////////////////////////////////////

	/**
	 * On crée l'enregistrement dans la MySQL et on renvoie la nouvelle ip
	 * IL FAUT AVOIR VÉRIFIÉ QUE LE NOM DE IP N'EXISTE PAS DÉJÀ
	 * Les liens des Children et Parents ne sont pas créés
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
			die("Impossible d'insérer la nouvelle ip, erreur MySQL : ".mysql_error());
		}
	}
	
}

?>