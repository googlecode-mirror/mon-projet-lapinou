-- 20 mars Cyril --

DROP TABLE IF EXISTS `lapin_lapin`; -- modif 15-3-13 : virer idlap : idlap +id_lapin ???? (+ nomlap
CREATE TABLE IF NOT EXISTS `lapin_lapin` (
  `id_lapin` int(11) NOT NULL AUTO_INCREMENT,
  `nomlap` varchar(30) NOT NULL COMMENT 'nom du lapin',
  `agelap` date NOT NULL COMMENT 'age', -- modif 20-3-13 passe en date
  `race` varchar(15) NOT NULL COMMENT 'race',
  `sexe` varchar(1) NOT NULL COMMENT 'sexe',
  `couleur` varchar(10) NOT NULL COMMENT 'couleur',
  `description` varchar(200) NOT NULL COMMENT 'description',
  `centreInteret` varchar(100) NOT NULL COMMENT 'interet',
  `photo` varchar(30) COMMENT 'photo',
  `identifiant` varchar(30) NOT NULL COMMENT 'identifiant du proprietaire',
  `id_profil` int NOT NULL COMMENT 'identifiant du proprietaire',
  PRIMARY KEY (`id_lapin`),
  foreign key (`identifiant`) references `lapin_proprietaire` (`identifiant`) on delete cascade
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
