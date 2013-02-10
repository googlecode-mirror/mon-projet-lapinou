-- creation base
CREATE DATABASE IF NOT EXISTS `LAPI.NET`;
USE `LAPI.NET`;

-- table utilisateur
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `nom` varchar(20) NOT NULL COMMENT 'nom de l''utilisateur',
  `date_dernier_signal` datetime COMMENT 'date dernier signal',
  `date_acces_session` datetime COMMENT 'variable de session representant la date',
  PRIMARY KEY (`nom`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- table conversation
CREATE TABLE IF NOT EXISTS `conversation` (
  `id_conversation` int(11) NOT NULL AUTO_INCREMENT COMMENT 'numero de fil de conversation',
  `user1` varchar(20) NOT NULL COMMENT 'utilisateur 1',
  `session_1` datetime NOT NULL COMMENT 'date de la session (user1)',
  `user2` varchar(20) NOT NULL COMMENT 'utilisateur 2',
  `session_2` datetime NOT NULL COMMENT 'date de la session (user2)',
  PRIMARY KEY (`id_conversation`),
  FOREIGN KEY (`user1`) REFERENCES `utilisateur` (`nom`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`user2`) REFERENCES `utilisateur` (`nom`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- table message
CREATE TABLE IF NOT EXISTS `message` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT COMMENT 'numero id du message',
  `conversation` int(11) NOT NULL COMMENT 'numero de la conversation courante',
  `expediteur` varchar(20) NOT NULL COMMENT 'id exped',
  `destinataire` varchar(20) NOT NULL COMMENT 'id dest',
  `texte` text COMMENT 'contenu',
  `date` datetime NOT NULL COMMENT 'date de l''envoi',
  PRIMARY KEY (`id_message`),
  FOREIGN KEY (`conversation`) REFERENCES `conversation` (`id_conversation`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`expediteur`) REFERENCES `utilisateur` (`nom`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`destinataire`) REFERENCES `utilisateur` (`nom`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
