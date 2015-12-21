--
-- Structure de la table `module_` (appariement externe)
--

CREATE TABLE  `abonnement` (
  `type` VARCHAR( 255 ) NOT NULL,
  `user_id` INT( 11 ) NOT NULL,
  `classeur_id` INT( 11 ) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL,
  `active` TINYINT(1) DEFAULT 1 NOT NULL,
  PRIMARY KEY(`type`, `user_id`, `classeur_id`)
) ENGINE = INNODB;