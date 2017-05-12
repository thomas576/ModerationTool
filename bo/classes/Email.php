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
	// Méthodes statiques de création dans la MySQL //
	//////////////////////////////////////////////////

	/**
	 * On crée l'enregistrement dans la MySQL et on renvoie le nouveau email
	 * IL FAUT AVOIR VÉRIFIÉ QUE LE NOM DE EMAIL N'EXISTE PAS DÉJÀ
	 * Les liens des Children et Parents ne sont pas créés
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
			die("Impossible d'insérer le nouvel email, erreur MySQL : ".mysql_error());
		}
	}
	
}

?>