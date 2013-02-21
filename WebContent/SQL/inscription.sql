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
  `passwd` varchar(40) NOT NULL COMMENT 'mot de passe', -- modif dom 02-12-2013 : m√©morise un code de hashage sha1
  `cle` int(10) NULL COMMENT 'cle aleatoire pour le hashage initialis√© √† chaque login',
  PRIMARY KEY (`identifiant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- table lapin

CREATE TABLE IF NOT EXISTS `lapin` (
  `idLap` varchar(30) NOT NULL COMMENT 'nom d''utilisateur du lapin',
  `nomlap` varchar(30) NOT NULL COMMENT 'nom du lapin',
  `agelap` varchar(2) NOT NULL COMMENT 'age',
  `race` varchar(15) NOT NULL COMMENT 'race',
  `sexe` int(1) NOT NULL COMMENT 'sexe',
  `couleur` varchar(10) NOT NULL COMMENT 'couleur',
  `description` varchar(200) NOT NULL COMMENT 'description',
  `centreInteret` varchar(100) NOT NULL COMMENT 'interet',
  `dateSession` date NOT NULL COMMENT 'dateS',
  `dateRaffraich` date NOT NULL COMMENT 'dateR',
  `regionlap` varchar(30) DEFAULT NULL COMMENT 'region du lapin',
  `maillap` varchar(60) NOT NULL COMMENT 'mail du lapi,',
  `passwdlap` varchar(40) NOT NULL COMMENT 'mot de passe du lapin', 
  `clelap` int(10) NULL COMMENT 'cle aleatoire pour le hashage du lapin initialisÈe ‡ chaque login',
  `identifiant` varchar(30) NOT NULL COMMENT 'identifiant du proprietaire',
  PRIMARY KEY (`idLap`)
  constraint FKLapinProprio foreign key (identifiant) references proprietaire (identifiant) on delete cascade);
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


                                       