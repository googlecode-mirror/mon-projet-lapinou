-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Sam 02 Mars 2013 à 18:35
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
-- Structure de la table `lapin_appartientA`
--

DROP TABLE IF EXISTS `lapin_appartientA`;
CREATE TABLE IF NOT EXISTS `lapin_appartientA` (
  `id_disc` int(11) NOT NULL,
  `id_profil` int(11) NOT NULL,
  PRIMARY KEY (`id_disc`,`id_profil`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `lapin_aSupprimer`
--

DROP TABLE IF EXISTS `lapin_aSupprimer`;
CREATE TABLE IF NOT EXISTS `lapin_aSupprimer` (
  `id_profil` int(11) NOT NULL,
  `id_mess` int(11) NOT NULL,
  PRIMARY KEY (`id_profil`,`id_mess`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `lapin_conversation`
--

DROP TABLE IF EXISTS `lapin_conversation`;
CREATE TABLE IF NOT EXISTS `lapin_conversation` (
  `id_conversation` int(11) NOT NULL AUTO_INCREMENT COMMENT 'numero de fil de conversation',
  `user1` varchar(20) NOT NULL COMMENT 'utilisateur 1',
  `session_1` datetime NOT NULL COMMENT 'date de la session (user1)',
  `user2` varchar(20) NOT NULL COMMENT 'utilisateur 2',
  `session_2` datetime NOT NULL COMMENT 'date de la session (user2)',
  PRIMARY KEY (`id_conversation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `lapin_Discussion`
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Structure de la table `lapin_lapin`
--

DROP TABLE IF EXISTS `lapin_lapin`;
CREATE TABLE IF NOT EXISTS `lapin_lapin` (
  `idLap` int(30) NOT NULL AUTO_INCREMENT COMMENT 'nom d''utilisateur du lapin',
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
  `clelap` int(10) DEFAULT NULL COMMENT 'cle aleatoire pour le hashage du lapin initialis�e � chaque login',
  `id_profil` int(30) NOT NULL COMMENT 'identifiant du proprietaire',
  PRIMARY KEY (`idLap`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Structure de la table `lapin_Message`
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- Structure de la table `lapin_message`
--

DROP TABLE IF EXISTS `lapin_message`;
CREATE TABLE IF NOT EXISTS `lapin_message` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT COMMENT 'numero id du message',
  `conversation` int(11) NOT NULL COMMENT 'numero de la conversation courante',
  `expediteur` varchar(20) NOT NULL COMMENT 'id exped',
  `destinataire` varchar(20) NOT NULL COMMENT 'id dest',
  `texte` text COMMENT 'contenu',
  `date` datetime NOT NULL COMMENT 'date de l''envoi',
  PRIMARY KEY (`id_message`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `lapin_proprietaire`
--

DROP TABLE IF EXISTS `lapin_proprietaire`;
CREATE TABLE IF NOT EXISTS `lapin_proprietaire` (
  `id_profil` int(11) NOT NULL AUTO_INCREMENT,
  `identifiant` varchar(30) NOT NULL COMMENT 'nom d''utilisateur',
  `nom` varchar(30) NOT NULL COMMENT 'nom',
  `prenom` varchar(30) NOT NULL COMMENT 'prenom',
  `code_postal` int(5) NOT NULL COMMENT 'code postal',
  `region` varchar(30) DEFAULT NULL COMMENT 'region',
  `mail` varchar(60) NOT NULL COMMENT 'mail',
  `passwd` varchar(40) NOT NULL COMMENT 'mot de passe',
  PRIMARY KEY (`id_profil`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `lapin_repondA`
--

DROP TABLE IF EXISTS `lapin_repondA`;
CREATE TABLE IF NOT EXISTS `lapin_repondA` (
  `src` int(11) NOT NULL,
  `rep` int(11) NOT NULL,
  PRIMARY KEY (`src`,`rep`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `lapin_utilisateur`
--

DROP TABLE IF EXISTS `lapin_utilisateur`;
CREATE TABLE IF NOT EXISTS `lapin_utilisateur` (
  `nom` varchar(20) NOT NULL COMMENT 'nom de l''utilisateur',
  `date_dernier_signal` datetime DEFAULT NULL COMMENT 'date dernier signal',
  `date_acces_session` datetime DEFAULT NULL COMMENT 'variable de session representant la date',
  PRIMARY KEY (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
