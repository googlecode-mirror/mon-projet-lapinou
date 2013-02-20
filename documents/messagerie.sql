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

DROP TABLE IF EXISTS `lapin_Lapin`;
CREATE TABLE IF NOT EXISTS `lapin_Lapin` (
  `id_lapin` int(11) NOT NULL AUTO_INCREMENT,
  `id_profil` int(11) NOT NULL,
  `nomL` varchar(32) NOT NULL,
  PRIMARY KEY (`id_lapin`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
