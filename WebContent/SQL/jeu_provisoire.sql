-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Jeu 14 Mars 2013 à 17:04
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
-- Contenu de la table `lapin_Consultation`
--

INSERT INTO `lapin_Consultation` (`id_profil`, `derniere`) VALUES
(3, '2013-02-23 18:36:49'),
(1, '2013-03-13 10:23:43');

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

INSERT INTO `lapin_lapin` (`id_lapin`, `idLap`, `nomlap`, `agelap`, `race`, `sexe`, `couleur`, `description`, `centreInteret`, `photo`, `identifiant`, `id_profil`) VALUES
(4, 'Buck', 'Buck', '', '', '0', '', '', '', '', 'root', 1),
(5, 'Bux', 'Bux', '', '', '0', '', '', '', '', 'Jeannot', 2),
(2, 'Civet', 'Civet', '', '', '0', '', '', '', '', 'root', 1),
(1, 'Jazz Jack', 'Jazz Jack', '', '', '0', '', '', '', '', 'root', 1),
(3, 'Jeannot', 'Jeannot', '', '', '0', '', '', '', '', 'root', 1),
(6, 'Panpan', 'Panpan', '', '', '0', '', '', '', '', 'Jazz Jack', 3),
(7, 'Roger', 'Roger', '', '', '0', '', '', '', '', 'Roger', 4),
(8, 'Serpolet', 'Serpolet', '', '', '0', '', '', '', '', 'Roger', 4);

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
(40, 'lancement', 'discussion', '2013-02-28 18:29:39', 6, 2),
(41, 'youpi !', 'Tralala.', '2013-03-12 10:48:46', 1, 1),
(42, 'RÃ©ponse', 'au message', '2013-03-12 11:21:01', 4, 1),
(43, 'test', 'nouveaux', '2013-03-12 11:36:22', 2, 1),
(44, 'test', 'nouveaux', '2013-03-12 11:39:07', 2, 1),
(45, 'essai', 'correction', '2013-03-12 12:00:22', 2, 1),
(46, 'plus', 'ajout', '2013-03-12 16:31:44', 1, 1),
(47, 'plus', '@ ', '2013-03-12 16:35:10', 1, 1);

--
-- Contenu de la table `lapin_proprietaire`
--

INSERT INTO `lapin_proprietaire` (`id_profil`, `identifiant`, `nom`, `prenom`, `code_postal`, `region`, `mail`, `passwd`, `date_dernier_signal`, `date_acces_session`, `trombine`) VALUES
(5, 'BIDON', 'BIDON', 'DONBI', 45000, 'Centre', 'bidon@gmail.com', '8a5ec1f532454cb56a023cbddf5129aa73f7f096', '2013-03-13 10:21:17', '2013-03-13 10:19:30', NULL),
(3, 'Jazz Jack', 'Jazz', 'Jack', 8000, NULL, '', '906f116eb480c6b710c2a0197b644afb372d34d3', NULL, NULL, NULL),
(2, 'Jeannot', 'Jeannot', '', 45000, NULL, '', '906f116eb480c6b710c2a0197b644afb372d34d3', NULL, NULL, NULL),
(4, 'Roger', 'Roger', '', 27000, NULL, '', '906f116eb480c6b710c2a0197b644afb372d34d3', NULL, NULL, NULL),
(1, 'root', 'Panpan', '', 0, NULL, '', '906f116eb480c6b710c2a0197b644afb372d34d3', '2013-03-14 17:04:03', '2013-03-14 12:17:02', NULL);

--
-- Contenu de la table `lapin_repondA`
--


--
-- Contenu de la table `lapin_tchat_conversation`
--


--
-- Contenu de la table `lapin_tchat_message`
--

