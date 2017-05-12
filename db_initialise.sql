-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Mar 05 Août 2008 à 22:23
-- Version du serveur: 5.0.41
-- Version de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de données: `bo`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `articles`
-- 

CREATE TABLE `articles` (
  `id` int(10) NOT NULL auto_increment,
  `nom` varchar(200) collate utf8_unicode_ci NOT NULL,
  `texte` text collate utf8_unicode_ci NOT NULL,
  `time` int(10) NOT NULL,
  `N` int(10) NOT NULL,
  `A` int(10) NOT NULL,
  `V` int(10) NOT NULL,
  `R` int(10) NOT NULL,
  `parents` text collate utf8_unicode_ci NOT NULL,
  `children` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `articles`
-- 

INSERT INTO `articles` VALUES (1, 'Moscou rend hommage  à Soljenitsyne', 'Avant les obsèques religieuses mercredi, quelques milliers de Russes, dont Vladimir Poutine, sont venus se recueillir devant la dépouille de l''écrivain, décédé dimanche.\r\n\r\nQuelques milliers de Russes bravaient mardi la pluie battante pour pouvoir rendre hommage à Alexandre Soljenitsyne, dont la dépouille était exposée dans un hall de l''Académie des Sciences de Moscou, avec pour seul bruit de fond une musique funèbre presque inaudible et un cérémonial digne de funérailles nationales.\r\n\r\nLe cercueil du prix Nobel de littérature 1970 est recouvert de fleurs et flanqué d''une garde d''honneur militaire devant un grand portrait en noir et blanc. Près de la dépouille, la veuve du prix Nobel de littérature, Natalia, entourée de ses fils et petits-enfants, avait du mal à retenir ses larmes.\r\n\r\nL''ancien président russe Vladimir Poutine, aujourd''hui premier ministre, est ainsi venu se recueillir devant le cercueil ouvert de l''écrivain, conformément à la tradition orthodoxe, et a déposé un bouquet de roses rouges. Poutine avait rendu plusieurs fois visite à l''écrivain pendant sa présidence, non sans que cela suscite quelques grincements de dents parmi les anciens dissidents en raison de son passé d''officier du KGB. Depuis le décès d''Alexandre Soljenitsyne, il a largement éclipsé le président Dmitri Medvedev dans l''hommage officiel rendu à l''écrivain, saluant le premier sa mémoire lundi en qualifiant sa disparition de « grande perte pour la Russie ».\r\n\r\n«Mon auteur préféré»\r\nAlexandre Soljenitsyne, qui s''est éteint dimanche chez lui près de Moscou, à l''âge de 89 ans, doit être enterré mercredi au monastère Donskoï de Moscou. Mardi, les Russes pourront se recueillir devant sa dépouille jusqu''à 19h00 locales.\r\n\r\nUne foule d''anonymes attendait pour venir se recueillir devant sa dépouille et déposer des fleurs au pied de la bière. «C''est un grand citoyen russe et mon auteur préféré», expliquait Evguéni Bistrov, 56 ans, qui patientait sous la pluie, un grand parapluie noir dans une main et des œillets rouges dans l''autre. «Mon roman préféré c''est ‘Le Pavillon des cancéreux''. Il y a tant d''optimisme dedans, de rage de vivre. Il ne vous impose jamais ses idées, mais il préfère vous donner à réfléchir et vous en venez à comprendre vous-même son sérieux et sa grandeur».\r\n\r\nLa plupart des personnes venues se recueillir étaient âgées de cinquante ans et plus, des Russes qui avaient lu les romans de Soljenitsyne à leur sortie dans les années 60 et 70.\r\n\r\n«Des gens qui comprenaient la vie comme lui étaient notre dernier espoir», expliquait une femme aux cheveux gris qui s''est présentée comme une simple enseignante, sans vouloir donner son nom. «Aujourd''hui, les jeunes grandissent sans rien savoir».', 1217967770, 0, 0, 0, 0, 'rub-1', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `commentaires`
-- 

CREATE TABLE `commentaires` (
  `id` int(10) NOT NULL auto_increment,
  `nom` varchar(200) collate utf8_unicode_ci NOT NULL,
  `texte` text collate utf8_unicode_ci NOT NULL,
  `remarque` text collate utf8_unicode_ci NOT NULL,
  `time` int(12) NOT NULL,
  `LR` int(10) NOT NULL,
  `LN` int(10) NOT NULL,
  `parents` text collate utf8_unicode_ci NOT NULL,
  `children` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `commentaires`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `emails`
-- 

CREATE TABLE `emails` (
  `id` int(10) NOT NULL auto_increment,
  `nom` varchar(200) collate utf8_unicode_ci NOT NULL,
  `time` int(10) NOT NULL,
  `N` int(10) NOT NULL,
  `A` int(10) NOT NULL,
  `V` int(10) NOT NULL,
  `R` int(10) NOT NULL,
  `parents` text collate utf8_unicode_ci NOT NULL,
  `children` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `emails`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `ips`
-- 

CREATE TABLE `ips` (
  `id` int(10) NOT NULL auto_increment,
  `nom` varchar(20) collate utf8_unicode_ci NOT NULL,
  `time` int(10) NOT NULL,
  `N` int(10) NOT NULL,
  `A` int(10) NOT NULL,
  `V` int(10) NOT NULL,
  `R` int(10) NOT NULL,
  `parents` text collate utf8_unicode_ci NOT NULL,
  `children` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `ips`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `listes`
-- 

CREATE TABLE `listes` (
  `id` int(10) NOT NULL auto_increment,
  `nom` varchar(200) collate utf8_unicode_ci NOT NULL,
  `parents` text collate utf8_unicode_ci NOT NULL,
  `children` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

-- 
-- Contenu de la table `listes`
-- 

INSERT INTO `listes` VALUES (1, 'N_COM', '', '');
INSERT INTO `listes` VALUES (2, 'A_COM', '', '');
INSERT INTO `listes` VALUES (3, 'V_COM', '', '');
INSERT INTO `listes` VALUES (4, 'R_COM', '', '');
INSERT INTO `listes` VALUES (5, 'LR_MOT', '', '');
INSERT INTO `listes` VALUES (6, 'LN_MOT', '', '');
INSERT INTO `listes` VALUES (7, 'OK_PSE', '', '');
INSERT INTO `listes` VALUES (8, 'LR_PSE', '', '');
INSERT INTO `listes` VALUES (9, 'LN_PSE', '', '');
INSERT INTO `listes` VALUES (10, 'OK_EMA', '', '');
INSERT INTO `listes` VALUES (11, 'LR_EMA', '', '');
INSERT INTO `listes` VALUES (12, 'LN_EMA', '', '');
INSERT INTO `listes` VALUES (13, 'OK_IPA', '', '');
INSERT INTO `listes` VALUES (14, 'LR_IPA', '', '');
INSERT INTO `listes` VALUES (15, 'LN_IPA', '', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `mots`
-- 

CREATE TABLE `mots` (
  `id` int(10) NOT NULL auto_increment,
  `nom` varchar(200) collate utf8_unicode_ci NOT NULL,
  `parents` text collate utf8_unicode_ci NOT NULL,
  `children` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `mots`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `pseudos`
-- 

CREATE TABLE `pseudos` (
  `id` int(10) NOT NULL auto_increment,
  `nom` varchar(200) collate utf8_unicode_ci NOT NULL,
  `time` int(10) NOT NULL,
  `N` int(10) NOT NULL,
  `A` int(10) NOT NULL,
  `V` int(10) NOT NULL,
  `R` int(10) NOT NULL,
  `parents` text collate utf8_unicode_ci NOT NULL,
  `children` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `pseudos`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `rubriques`
-- 

CREATE TABLE `rubriques` (
  `id` int(10) NOT NULL auto_increment,
  `nom` varchar(200) collate utf8_unicode_ci NOT NULL,
  `time` int(10) NOT NULL,
  `N` int(10) NOT NULL,
  `A` int(10) NOT NULL,
  `V` int(10) NOT NULL,
  `R` int(10) NOT NULL,
  `parents` text collate utf8_unicode_ci NOT NULL,
  `children` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- 
-- Contenu de la table `rubriques`
-- 

INSERT INTO `rubriques` VALUES (1, 'Politique', 1217887481, 0, 0, 0, 0, 'sit-1', 'art-1');
INSERT INTO `rubriques` VALUES (2, 'International', 1217887481, 0, 0, 0, 0, 'sit-1', '');
INSERT INTO `rubriques` VALUES (3, 'Sports', 1217887481, 0, 0, 0, 0, 'sit-1', '');
INSERT INTO `rubriques` VALUES (4, 'Économie', 1217887481, 0, 0, 0, 0, 'sit-1', '');
INSERT INTO `rubriques` VALUES (5, 'Culture', 1217887481, 0, 0, 0, 0, 'sit-1', '');
INSERT INTO `rubriques` VALUES (6, 'High-Tech', 1217887481, 0, 0, 0, 0, 'sit-1', '');
INSERT INTO `rubriques` VALUES (7, 'Sciences', 1217887481, 0, 0, 0, 0, 'sit-1', '');
INSERT INTO `rubriques` VALUES (8, 'Emploi', 1217887481, 0, 0, 0, 0, 'sit-1', '');
INSERT INTO `rubriques` VALUES (9, 'Voyages', 1217887481, 0, 0, 0, 0, 'sit-1', '');
INSERT INTO `rubriques` VALUES (10, 'Débats', 1217887481, 0, 0, 0, 0, 'sit-1', '');
INSERT INTO `rubriques` VALUES (11, 'Médias', 1217887481, 0, 0, 0, 0, 'sit-1', '');
INSERT INTO `rubriques` VALUES (12, 'Blogs', 1217887481, 0, 0, 0, 0, 'sit-1', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `sites`
-- 

CREATE TABLE `sites` (
  `id` int(10) NOT NULL auto_increment,
  `nom` varchar(200) collate utf8_unicode_ci NOT NULL,
  `N` int(10) NOT NULL,
  `A` int(10) NOT NULL,
  `V` int(10) NOT NULL,
  `R` int(10) NOT NULL,
  `parents` text collate utf8_unicode_ci NOT NULL,
  `children` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `sites`
-- 

INSERT INTO `sites` VALUES (1, 'Le Télégramme', 0, 0, 0, 0, '', 'rub-1 rub-2 rub-3 rub-4 rub-5 rub-6 rub-7 rub-8 rub-9 rub-10 rub-11 rub-12');
