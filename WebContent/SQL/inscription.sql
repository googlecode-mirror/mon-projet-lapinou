-- creation base
CREATE DATABASE IF NOT EXISTS `LAPI.NET`;
USE `LAPI.NET`;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- table proprietaire

CREATE TABLE IF NOT EXISTS `proprietaire` (
  `identifiant` varchar(30) NOT NULL COMMENT 'nom d''utilisateur',
  `nom` varchar(30) NOT NULL COMMENT 'nom',
  `prenom` varchar(30) NOT NULL COMMENT 'prenom',
  `code_postal` int(5) NOT NULL COMMENT 'code postal',
  `region` varchar(30) DEFAULT NULL COMMENT 'region',
  `mail` varchar(60) NOT NULL COMMENT 'mail',
  `passwd` varchar(40) NOT NULL COMMENT 'mot de passe', -- modif dom 02-12-2013 : mémorise un code de hashage sha1
  `cle` int(10) NULL COMMENT 'cle aleatoire pour le hashage initialisé à chaque login',
  PRIMARY KEY (`identifiant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
