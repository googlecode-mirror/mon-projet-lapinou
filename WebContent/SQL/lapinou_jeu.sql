-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Sam 02 Mars 2013 à 18:37
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

--
-- Contenu de la table `lapin_appartientA`
--


--
-- Contenu de la table `lapin_aSupprimer`
--


--
-- Contenu de la table `lapin_conversation`
--


--
-- Contenu de la table `lapin_Discussion`
--

INSERT INTO `lapin_Discussion` (`id_disc`, `sujet`, `date`, `intitule`, `auteur`, `dest`) VALUES
(1, 'Jazz Jack', '2013-02-09 18:43:17', 'titre', 3, 1),
(2, 'Jazz Jack', '2013-02-09 20:48:17', 'intitule', 3, 1),
(3, 'Jazz Jack', '2013-02-09 20:48:34', 'test', 3, 5),
(4, 'Jazz Jack', '2013-02-09 20:48:53', 'bis repetita', 3, 1),
(5, 'nouveautÃ©', '2013-02-28 18:27:46', 'lancement', 2, 8),
(6, 'nouveautÃ©', '2013-02-28 18:29:39', 'lancement', 2, 8);

--
-- Contenu de la table `lapin_lapin`
--

INSERT INTO `lapin_lapin` (`idLap`, `nomlap`, `agelap`, `race`, `sexe`, `couleur`, `description`, `centreInteret`, `dateSession`, `dateRaffraich`, `regionlap`, `maillap`, `passwdlap`, `clelap`, `id_profil`) VALUES
(1, 'Jazz Jack', '', '', 0, '', '', '', '0000-00-00', '0000-00-00', NULL, '', '', NULL, 1),
(2, 'Civet', '', '', 0, '', '', '', '0000-00-00', '0000-00-00', NULL, '', '', NULL, 1),
(3, 'Jeannot', '', '', 0, '', '', '', '0000-00-00', '0000-00-00', NULL, '', '', NULL, 1),
(4, 'Buck', '', '', 0, '', '', '', '0000-00-00', '0000-00-00', NULL, '', '', NULL, 1),
(5, 'Bux', '', '', 0, '', '', '', '0000-00-00', '0000-00-00', NULL, '', '', NULL, 2),
(6, 'Panpan', '', '', 0, '', '', '', '0000-00-00', '0000-00-00', NULL, '', '', NULL, 3),
(7, 'Roger', '', '', 0, '', '', '', '0000-00-00', '0000-00-00', NULL, '', '', NULL, 4),
(8, 'Serpolet', '', '', 0, '', '', '', '0000-00-00', '0000-00-00', NULL, '', '', NULL, 4);

--
-- Contenu de la table `lapin_Message`
--

INSERT INTO `lapin_Message` (`id_mess`, `titre`, `texte`, `date`, `id_disc`, `id_lapin`) VALUES
(2, 'intitule', 'bla bla', '0000-00-00 00:00:00', 2, 3),
(3, 'test', 'Coucou !', '0000-00-00 00:00:00', 3, 3),
(4, 'bis repetita', 'remplissage', '0000-00-00 00:00:00', 4, 3),
(31, 'fghjh', 'fk lgl', '2013-02-18 02:00:43', 1, 1),
(32, 'hgjf', 'h lgh', '2013-02-18 02:01:12', 2, 1),
(33, 'fstgfgergser', 'fesrqse', '2013-02-20 12:06:39', 2, 1),
(34, 'sujet', 'fklerl', '2013-02-28 11:42:33', 2, 1),
(35, 'sujet', 'corps', '2013-02-28 11:43:25', 2, 1),
(36, 'fqfrf', 'gvsfg ss', '2013-02-28 12:10:43', 2, 1),
(37, 'test', 'rÃ©ouverture', '2013-02-28 15:36:45', 2, 1),
(38, 'bis', 'repetita', '2013-02-28 15:43:48', 4, 1),
(39, 'ter', 'minal', '2013-02-28 15:46:17', 4, 1),
(40, 'lancement', 'discussion', '2013-02-28 18:29:39', 6, 2);

--
-- Contenu de la table `lapin_message`
--


--
-- Contenu de la table `lapin_proprietaire`
--

INSERT INTO `lapin_proprietaire` (`id_profil`, `identifiant`, `nom`, `prenom`, `code_postal`, `region`, `mail`, `passwd`) VALUES
(1, 'root', 'Panpan', '', 0, NULL, '', '906f116eb480c6b710c2a0197b644afb372d34d3'),
(2, 'Jeannot', 'Jeannot', '', 45000, NULL, '', '906f116eb480c6b710c2a0197b644afb372d34d3'),
(3, 'Jazz Jack', 'Jazz', 'Jack', 8000, NULL, '', '906f116eb480c6b710c2a0197b644afb372d34d3'),
(4, 'Roger', 'Roger', '', 27000, NULL, '', '906f116eb480c6b710c2a0197b644afb372d34d3');

--
-- Contenu de la table `lapin_repondA`
--


--
-- Contenu de la table `lapin_utilisateur`
--

