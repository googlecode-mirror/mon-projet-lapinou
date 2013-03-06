-- creation base
-- USE `arnould_f`;



-- tables du chat

-- table conversation
CREATE TABLE IF NOT EXISTS `lapin_tchat_conversation` (
  `id_conversation` int(11) NOT NULL AUTO_INCREMENT COMMENT 'numero de fil de conversation',
  `user1` varchar(20) NOT NULL COMMENT 'utilisateur 1',
  `session_1` datetime NOT NULL COMMENT 'date de la session (user1)',
  `user2` varchar(20) NOT NULL COMMENT 'utilisateur 2',
  `session_2` datetime NOT NULL COMMENT 'date de la session (user2)',
  PRIMARY KEY (`id_conversation`),
  FOREIGN KEY (`user1`) REFERENCES `lapin_proprietaire` (`identifiant`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`user2`) REFERENCES `lapin_proprietaire` (`identifiant`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- table message
CREATE TABLE IF NOT EXISTS `lapin_tchat_message` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT COMMENT 'numero id du message',
  `conversation` int(11) NOT NULL COMMENT 'numero de la conversation courante',
  `expediteur` varchar(20) NOT NULL COMMENT 'id exped',
  `destinataire` varchar(20) NOT NULL COMMENT 'id dest',
  `texte` text COMMENT 'contenu',
  `date` datetime NOT NULL COMMENT 'date de l''envoi',
  PRIMARY KEY (`id_message`),
  FOREIGN KEY (`conversation`) REFERENCES `lapin_tchat_conversation` (`id_conversation`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`expediteur`) REFERENCES `lapin_proprietaire` (`identifiant`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`destinataire`) REFERENCES `lapin_proprietaire` (`identifiant`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



-- tables d'inscription



SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- table proprietaire

CREATE TABLE IF NOT EXISTS `lapin_proprietaire` (
  `identifiant` varchar(30) NOT NULL COMMENT 'nom d''utilisateur',
  `nom` varchar(30) NOT NULL COMMENT 'nom',
  `prenom` varchar(30) NOT NULL COMMENT 'prenom',
  `code_postal` int(5) NOT NULL COMMENT 'code postal',
  `region` varchar(30) DEFAULT NULL COMMENT 'region',
  `mail` varchar(60) NOT NULL COMMENT 'mail',
  `passwd` varchar(40) NOT NULL COMMENT 'mot de passe', -- modif dom 02-12-2013 : memorise un code de hashage sha1
  `date_dernier_signal` datetime COMMENT 'date dernier signal',
  `date_acces_session` datetime COMMENT 'variable de session representant la date',
  PRIMARY KEY (`identifiant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- table lapin

CREATE TABLE IF NOT EXISTS `lapin_lapin` (
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
  `clelap` int(10) NULL COMMENT 'cle aleatoire pour le hashage du lapin initialis�e � chaque login',
  `identifiant` varchar(30) NOT NULL COMMENT 'identifiant du proprietaire',
  PRIMARY KEY (`idLap`),
  foreign key (`identifiant`) references `lapin_proprietaire` (`identifiant`) on delete cascade
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- tables de la messagerie



-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mar 19 Février 2013 à 00:40
-- Version du serveur: 5.1.67
-- Version de PHP: 5.3.2-1ubuntu4.18

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `lapinou`
--

-- --------------------------------------------------------

--
-- Structure de la table `appartientA`
--

DROP TABLE IF EXISTS `lapin_appartientA`;
CREATE TABLE IF NOT EXISTS `lapin_appartientA` (
  `id_disc` int(11) NOT NULL,
  `id_profil` int(11) NOT NULL,
  PRIMARY KEY (`id_disc`,`id_profil`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `aSupprimer`
--

DROP TABLE IF EXISTS `lapin_aSupprimer`;
CREATE TABLE IF NOT EXISTS `lapin_aSupprimer` (
  `id_profil` int(11) NOT NULL,
  `id_mess` int(11) NOT NULL,
  PRIMARY KEY (`id_profil`,`id_mess`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Discussion`
--

DROP TABLE IF EXISTS `lapin_Discussion`;
CREATE TABLE IF NOT EXISTS `lapin_Discussion` (
  `id_disc` int(11) NOT NULL AUTO_INCREMENT,
  `sujet` varchar(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `intitule` varchar(64) NOT NULL DEFAULT '',
  `auteur` int(11) NOT NULL,
  `dest` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_disc`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Lapin`
--

-- DROP TABLE IF EXISTS `lapin_Lapin`;
-- CREATE TABLE IF NOT EXISTS `lapin_Lapin` (
-- `id_lapin` int(11) NOT NULL AUTO_INCREMENT,
-- `id_profil` int(11) NOT NULL,
--  `nomL` varchar(32) NOT NULL,
--  PRIMARY KEY (`id_lapin`)
-- ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Message`
--

DROP TABLE IF EXISTS `lapin_Message`;
CREATE TABLE IF NOT EXISTS `lapin_Message` (
  `id_mess` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(64) NOT NULL,
  `texte` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_disc` int(11) NOT NULL,
  `id_lapin` int(11) NOT NULL,
  PRIMARY KEY (`id_mess`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Profil`
--

DROP TABLE IF EXISTS `lapin_Profil`;
CREATE TABLE IF NOT EXISTS `lapin_Profil` (
  `id_profil` int(11) NOT NULL AUTO_INCREMENT,
  `infos` text NOT NULL COMMENT 'pseudo-attribut à remplacer',
  PRIMARY KEY (`id_profil`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='table à reprendre de la définition commune du site' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `repondA`
--

DROP TABLE IF EXISTS `lapin_repondA`;
CREATE TABLE IF NOT EXISTS `lapin_repondA` (
  `src` int(11) NOT NULL,
  `rep` int(11) NOT NULL,
  PRIMARY KEY (`src`,`rep`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



-- --------------------------------------------------------



--
-- 			Jeu par défaut
--


--
-- Contenu de la table `lapin_proprietaire`
--

INSERT INTO `lapin_proprietaire` (`identifiant`, `nom`, `prenom`, `code_postal`, `region`, `mail`, `passwd`) VALUES
('root', '', '', 0, NULL, '', '906f116eb480c6b710c2a0197b644afb372d34d3');
