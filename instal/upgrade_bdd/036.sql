--
-- Structure de la table `module_sso` (appariement externe)
--

CREATE TABLE  `module_sso` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`id_externe` VARCHAR( 255 ) NOT NULL ,
`id_ecolenumerique` INT( 11 ) NOT NULL ,
`createdAt` DATETIME NULL DEFAULT NULL ,
`lastAccess` DATETIME NULL DEFAULT NULL
) ENGINE = INNODB;