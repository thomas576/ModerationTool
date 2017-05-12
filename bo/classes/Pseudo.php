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
	// Méthodes statiques de création dans la MySQL //
	//////////////////////////////////////////////////

	/**
	 * On crée l'enregistrement dans la MySQL et on renvoie le nouveau pseudo
	 * IL FAUT AVOIR VÉRIFIÉ QUE LE NOM DE PSEUDO N'EXISTE PAS DÉJÀ
	 * Les liens des Children et Parents ne sont pas créés
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
			die("Impossible d'insérer le nouveau pseudo, erreur MySQL : ".mysql_error());
		}
	}
}

?>