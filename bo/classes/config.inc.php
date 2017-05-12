<?php

// Connexion à MySQL
define("MYSQL_HOST", "localhost:3306");
define("MYSQL_USER", "root");
define("MYSQL_PASSWORD", "root");
define("MYSQL_BASE", "bo");

define("N_COM", "lis-1");
define("A_COM", "lis-2");
define("V_COM", "lis-3");
define("R_COM", "lis-4");
define("LR_MOT", "lis-5");
define("LN_MOT", "lis-6");
define("OK_PSE", "lis-7");
define("LR_PSE", "lis-8");
define("LN_PSE", "lis-9");
define("OK_EMA", "lis-10");
define("LR_EMA", "lis-11");
define("LN_EMA", "lis-12");
define("OK_IPA", "lis-13");
define("LR_IPA", "lis-14");
define("LN_IPA", "lis-15");

class Config {
	
	/**
	 * @var array
	 */
	static public $array_key_classes = array('sit' => 'Site', 'rub' => 'Rubrique', 'art' => 'Article', 'com' => 'Commentaire', 'pse' => 'Pseudo', 'ema' => 'Email', 'ipa' => 'Ip', 'lis' => 'Liste', 'mot' => 'Mot');
	/**
	 * @var array
	 */
	static public $array_tables_mysql_des_classes = array('Site' => 'sites', 'Rubrique' => 'rubriques', 'Article' => 'articles', 'Commentaire' => 'commentaires', 'Pseudo' => 'pseudos', 'Email' => 'emails', 'Ip' => 'ips', 'Liste' => 'listes', 'Mot' => 'mots');
	/**
	 * @var array
	 */
	static public $array_statuts_commentaires = array('N' => 'Nouveau', 'A' => 'En attente', 'V' => 'Validé', 'R' => 'Rejeté');
	/**
	 * @var array
	 */
	static public $array_listes_mots = array('OK' => 'Liste verte', 'LR' => 'Liste rouge', 'LN' => 'Liste noire');
	/**
	 * @var array
	 */
	static public $array_keys_noms_liste = array("lis-1" => 'N_COM', "lis-2" => 'A_COM', "lis-3" => 'V_COM', "lis-4" => 'R_COM', "lis-5" => 'LR_MOT', "lis-6" => 'LN_MOT', "lis-7" => 'OK_PSE', "lis-8" => 'LR_PSE', "lis-9" => 'LN_PSE', "lis-10" => 'OK_EMA', "lis-11" => 'LR_EMA', "lis-12" => 'LN_EMA', "lis-13" => 'OK_IPA', "lis-14" => 'LR_IPA', "lis-15" => 'LN_IPA');
	
}

?>