
INSERT INTO dbuser
	( id_dbuser, login_dbuser, password_dbuser, enabled_dbuser, email_dbuser )
VALUES
	( '2', 'jean', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '3', 'lucie', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '4', 'marc', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '5', 'luc', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '6', 'irene', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '7', 'eric', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '8', 'jules', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '9', 'fanny', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '10', 'charlotte', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '11', 'adeline', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '12', 'pfranc', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '13', 'mmeyer', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '14', 'dkutz', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '15', 'fgilet', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '16', 'mbraton', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '17', 'alenaick', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '18', 'mlenaick', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '19', 'tlany', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '20', 'jkheira', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '21', 'ckheira', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '22', 'jmvadia', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '23', 'okonul', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '24', 'adanuta', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '25', 'mmaira', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '26', 'amihel', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '27', 'jvacken', 'e10adc3949ba59abbe56e057f20f883e', '1', '' ),
	( '28', 'rvurgul', 'e10adc3949ba59abbe56e057f20f883e', '1', '' );


INSERT INTO `kernel_bu_civilite` VALUES (1, 'Monsieur', 'M');
INSERT INTO `kernel_bu_civilite` VALUES (2, 'Madame', 'Mme');
INSERT INTO `kernel_bu_civilite` VALUES (3, 'Mademoiselle', 'Mlle');

INSERT INTO `kernel_bu_eleve_etat` VALUES (1, 'Admission');
INSERT INTO `kernel_bu_eleve_etat` VALUES (2, 'Admissibilit�');
INSERT INTO `kernel_bu_eleve_etat` VALUES (3, 'Radiation');
INSERT INTO `kernel_bu_eleve_etat` VALUES (4, 'Non fr�quentation');

INSERT INTO `kernel_bu_ecole` VALUES (1, '', '', 'El�mentaire', 'Ecole du bois fleuri', '12', '', 'rue des fleurs', '', '67000', 'Granville', '01.02.99.33.44', 'http://', '', NULL, 0, 0, 1);
INSERT INTO `kernel_bu_ecole` VALUES (2, '', '', 'Maternelle', 'Maternelle Gutenberg', '4', '', 'boulevard des imprimeurs', '', '67100', 'Bourzac', '', 'http://', '', NULL, 0, 0, 1);
INSERT INTO `kernel_bu_ecole` VALUES (3, '', '', 'Primaire', 'Ecole Jules Ferry', '16', '', 'rue des �coliers', '', '67000', 'Granville', '', 'http://', '', NULL, 0, 0, 1);

INSERT INTO `kernel_bu_ecole_classe` VALUES (1, 1, 'CP bleu', 2010, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (2, 1, 'CE1 bleu', 2010, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (3, 1, 'CE2 vert', 2010, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (4, 1, 'CM1 rouge', 2010, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (5, 1, 'CM2 rose', 2010, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (6, 2, 'Ann�e 1', 2010, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (7, 2, 'Ann�e 2', 2010, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (8, 2, 'Ann�e 3', 2010, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (9, 3, 'CP', 2010, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (10, 3, 'CE1', 2010, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (11, 3, 'CE2', 2010, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (12, 3, 'CM1', 2010, 1, 0);
INSERT INTO `kernel_bu_ecole_classe` VALUES (13, 3, 'CM2', 2010, 1, 0);

INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (1, 5, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (2, 6, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (3, 7, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (4, 8, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (5, 7, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (6, 2, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (7, 3, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (8, 4, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (9, 5, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (10, 6, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (11, 7, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (12, 8, 11);
INSERT INTO `kernel_bu_ecole_classe_niveau` VALUES (13, 9, 11);

INSERT INTO `kernel_bu_eleve` VALUES (1, '20051229-105033-', NULL, 'Lenaick', NULL, 'Jean', '', '', 'Monsieur', 1, 'France', 1, '67', 'Strasbourg', '1999-02-12', NULL, '5', '', 'rue St Michel', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (2, '20051229-105446-', NULL, 'Lany', NULL, 'Lucie', '', '', 'Mademoiselle', 2, 'France', 1, '68', 'Mulhouse', '2000-10-10', NULL, '63', '', 'Avenue de la Marseillaise', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (3, '20051229-105909-', NULL, 'Kheira', NULL, 'Marc', '', '', 'Monsieur', 1, 'France', 1, '14', 'Caen', '1999-09-04', NULL, '15', '', 'rue des Roses', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (4, '20051229-110723-', NULL, 'Valia', NULL, 'Luc', '', '', 'Monsieur', 1, 'France', 1, '67', 'Strasbourg', '1999-11-19', NULL, '36', '', 'boulevard Mirabeau', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (5, '20051229-111144-', NULL, 'Konul', NULL, 'Ir�ne', '', '', 'Mademoiselle', 2, 'France', 1, '67', 'Strasbourg', '2000-03-30', NULL, '1', '', 'rue de la course', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (6, '20051229-111953-', NULL, 'Danuta', NULL, 'Eric', '', '', 'Monsieur', 1, 'France', 1, '67', 'Schiltigheim', '1998-01-10', NULL, '3', '', 'rue des pr�s', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (7, '20051229-112139-', NULL, 'Maira', NULL, 'Jules', '', '', 'Monsieur', 1, 'France', 1, '67', 'Schiltigheim', '1999-04-14', NULL, '20', '', 'avenue de Colmar', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (8, '20051229-112429-', NULL, 'Mihel', NULL, 'Fanny', '', '', 'Mademoiselle', 2, 'France', 1, '68', 'Mulhouse', '1997-02-15', NULL, '39', '', 'impasse des ch�teaux-forts', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (9, '20051229-112550-', NULL, 'Vacken', NULL, 'Charlotte', '', '', 'Mademoiselle', 2, 'France', 1, '75', 'Paris', '1997-07-27', NULL, '183', '', 'rue Curie', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());
INSERT INTO `kernel_bu_eleve` VALUES (10, '20051229-112718-', NULL, 'Vurgul', NULL, 'Adeline', '', '', 'Mademoiselle', 2, 'France', 1, '66', 'Perpignan', '1996-12-19', NULL, '15', '', 'foss� des tanneurs', '', '67000', 'Granville', 0, 1, 0, NULL, NULL, 0, NULL, NULL, NOW());

INSERT INTO `kernel_bu_eleve_admission` VALUES (1, 8, 1, 2010, 0, 1, '2010-08-29', '2010-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (2, 9, 1, 2010, 0, 1, '2010-08-29', '2010-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (3, 10, 1, 2010, 0, 1, '2010-08-29', '2010-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (4, 3, 1, 2010, 0, 1, '2010-08-29', '2010-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (5, 5, 1, 2010, 0, 1, '2010-08-29', '2010-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (6, 2, 1, 2010, 0, 1, '2010-08-29', '2010-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (7, 1, 1, 2010, 0, 1, '2010-08-29', '2010-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (8, 7, 1, 2010, 0, 1, '2010-08-29', '2010-09-01', 0, 0);
INSERT INTO `kernel_bu_eleve_admission` VALUES (9, 6, 1, 2010, 0, 1, '2010-08-29', '2010-09-01', 0, 0);

INSERT INTO `kernel_bu_eleve_affectation` VALUES (1, 8, 2010, 3, 7, '2010-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (2, 9, 2010, 3, 7, '2010-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (3, 10, 2010, 3, 7, '2010-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (4, 3, 2010, 1, 5, '2010-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (5, 5, 2010, 1, 5, '2010-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (6, 2, 2010, 1, 5, '2010-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (7, 1, 2010, 1, 5, '2010-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (8, 7, 2010, 1, 5, '2010-09-01', 1, 0);
INSERT INTO `kernel_bu_eleve_affectation` VALUES (9, 6, 2010, 3, 7, '2010-09-01', 1, 0);

INSERT INTO `kernel_bu_eleve_inscription` VALUES (1, 1, 2010, '2010-08-01', '2010-08-01', '2010-09-01', '2010-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (2, 2, 2010, '2010-08-01', '2010-08-01', '2010-09-01', '2010-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (3, 3, 2010, '2010-08-01', '2010-08-01', '2010-09-01', '2010-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (4, 4, 2010, '2010-08-01', '2010-08-01', '2010-09-01', '2010-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (5, 5, 2010, '2010-08-01', '2010-08-01', '2010-09-01', '2010-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (6, 6, 2010, '2010-08-01', '2010-08-01', '2010-09-01', '2010-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (7, 7, 2010, '2010-08-01', '2010-08-01', '2010-09-01', '2010-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (8, 8, 2010, '2010-08-01', '2010-08-01', '2010-09-01', '2010-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (9, 9, 2010, '2010-08-01', '2010-08-01', '2010-09-01', '2010-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);
INSERT INTO `kernel_bu_eleve_inscription` VALUES (10, 10, 2010, '2010-08-01', '2010-08-01', '2010-09-01', '2010-09-01', 1, 0, 0, 0, 0, 0, '0000-00-00', 0, 0, '0000-00-00', '0000-00-00', 0, '', 0, NULL, 1);

INSERT INTO `kernel_bu_personnel` VALUES (1, 'Franc', '', 'Pierre', 'Monsieur', 1, NULL, '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, NULL, NULL);
INSERT INTO `kernel_bu_personnel` VALUES (2, 'Meyer', '', 'Martine', 'Madame', 2, NULL, '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, NULL, NULL);
INSERT INTO `kernel_bu_personnel` VALUES (3, 'Kutz', '', 'Doroth�e', 'Mademoiselle', 2, NULL, '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, NULL, NULL);
INSERT INTO `kernel_bu_personnel` VALUES (4, 'Gilet', '', 'Franck', 'Monsieur', 1, NULL, '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, NULL, NULL);
INSERT INTO `kernel_bu_personnel` VALUES (5, 'Braton', '', 'Marc', 'Monsieur', 1, NULL, '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, NULL, NULL);

INSERT INTO `kernel_bu_personnel_entite` VALUES (1, 1, 'ECOLE', 2);
INSERT INTO `kernel_bu_personnel_entite` VALUES (1, 1, 'CLASSE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (2, 1, 'ECOLE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (2, 1, 'CLASSE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (3, 1, 'ECOLE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (3, 3, 'CLASSE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (4, 1, 'ECOLE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (4, 2, 'CLASSE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (4, 4, 'CLASSE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (4, 5, 'CLASSE', 1);
INSERT INTO `kernel_bu_personnel_entite` VALUES (5, 1, 'VILLE', 4);

INSERT INTO `kernel_bu_responsable` VALUES (1, 'Lenaick', '', 'Albert', 'Monsieur', 1, 0, '', 2, '', '', '', '', '5', '', 'rue St Michel', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (2, 'Lenaick', '', 'Martine', 'Madame', 2, 0, '', 2, '', '', '', '', '5', '', 'rue St Michel', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (3, 'Lany', '', 'Thierry', 'Monsieur', 1, 0, '', 3, '', '', '', '', '63', '', 'Avenue de la Marseillaise', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (4, 'Kheira', '', 'Jos�', 'Monsieur', 1, 0, '', 2, '', '', '', '', '15', '', 'rue des Roses', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (5, 'Kheira', '', 'Corinne', 'Madame', 2, 0, '', 2, '', '', '', '', '15', '', 'rue des Roses', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (6, 'Valia', '', 'Jean-Marc', 'Monsieur', 1, 33, '', 3, '', '', '', '', '36', '', 'boulevard Mirabeau', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (7, 'Konul', '', 'Odile', 'Madame', 2, 0, '', 4, '', '', '', '', '1', '', 'rue de la course', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (8, 'Danuta', '', 'Albert', 'Monsieur', 1, 0, '', 6, '', '', '', '', '3', '', 'rue des pr�s', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (9, 'Maira', '', 'Michelle', 'Madame', 2, 0, '', 6, '', '', '', '', '20', '', 'avenue de Colmar', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (10, 'Mihel', '', 'Andr�', 'Monsieur', 1, 0, '', 1, '', '', '', '', '39', '', 'impasse des ch�teaux-forts', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (11, 'Vacken', '', 'Jocelyne', 'Madame', 2, 0, '', 1, '', '', '', '', '183', '', 'rue Curie', '', '67000', 'Granville', 0);
INSERT INTO `kernel_bu_responsable` VALUES (12, 'Vurgul', '', 'Robert', 'Monsieur', 1, 34, '', 1, '', '', '', '', '15', '', 'foss� des tanneurs', '', '67000', 'Granville', 0);

INSERT INTO `kernel_bu_responsables` VALUES (1, 1, 'eleve', 1, 'responsable', 1, 2);
INSERT INTO `kernel_bu_responsables` VALUES (2, 1, 'eleve', 2, 'responsable', 1, 1);
INSERT INTO `kernel_bu_responsables` VALUES (3, 2, 'eleve', 3, 'responsable', 1, 2);
INSERT INTO `kernel_bu_responsables` VALUES (4, 3, 'eleve', 4, 'responsable', 1, 2);
INSERT INTO `kernel_bu_responsables` VALUES (5, 3, 'eleve', 5, 'responsable', 1, 1);
INSERT INTO `kernel_bu_responsables` VALUES (6, 4, 'eleve', 6, 'responsable', 1, 2);
INSERT INTO `kernel_bu_responsables` VALUES (7, 5, 'eleve', 7, 'responsable', 1, 1);
INSERT INTO `kernel_bu_responsables` VALUES (8, 6, 'eleve', 8, 'responsable', 1, 2);
INSERT INTO `kernel_bu_responsables` VALUES (9, 7, 'eleve', 9, 'responsable', 1, 1);
INSERT INTO `kernel_bu_responsables` VALUES (10, 8, 'eleve', 10, 'responsable', 1, 2);
INSERT INTO `kernel_bu_responsables` VALUES (11, 9, 'eleve', 11, 'responsable', 1, 1);
INSERT INTO `kernel_bu_responsables` VALUES (12, 10, 'eleve', 12, 'responsable', 1, 2);

INSERT INTO `kernel_bu_ville` VALUES (1, 'Granville', 'granville', 1, NOW());
INSERT INTO `kernel_bu_ville` VALUES (2, 'Bourzac', 'bourzac', 1, NOW());

INSERT INTO `kernel_link_bu2user` VALUES (2, 'USER_ELE', 1);
INSERT INTO `kernel_link_bu2user` VALUES (3, 'USER_ELE', 2);
INSERT INTO `kernel_link_bu2user` VALUES (4, 'USER_ELE', 3);
INSERT INTO `kernel_link_bu2user` VALUES (5, 'USER_ELE', 4);
INSERT INTO `kernel_link_bu2user` VALUES (6, 'USER_ELE', 5);
INSERT INTO `kernel_link_bu2user` VALUES (7, 'USER_ELE', 6);
INSERT INTO `kernel_link_bu2user` VALUES (8, 'USER_ELE', 7);
INSERT INTO `kernel_link_bu2user` VALUES (9, 'USER_ELE', 8);
INSERT INTO `kernel_link_bu2user` VALUES (10, 'USER_ELE', 9);
INSERT INTO `kernel_link_bu2user` VALUES (11, 'USER_ELE', 10);
INSERT INTO `kernel_link_bu2user` VALUES (12, 'USER_ENS', 1);
INSERT INTO `kernel_link_bu2user` VALUES (13, 'USER_ENS', 2);
INSERT INTO `kernel_link_bu2user` VALUES (14, 'USER_ENS', 3);
INSERT INTO `kernel_link_bu2user` VALUES (15, 'USER_ENS', 4);
INSERT INTO `kernel_link_bu2user` VALUES (16, 'USER_VIL', 5);
INSERT INTO `kernel_link_bu2user` VALUES (17, 'USER_RES', 1);
INSERT INTO `kernel_link_bu2user` VALUES (18, 'USER_RES', 2);
INSERT INTO `kernel_link_bu2user` VALUES (19, 'USER_RES', 3);
INSERT INTO `kernel_link_bu2user` VALUES (20, 'USER_RES', 4);
INSERT INTO `kernel_link_bu2user` VALUES (21, 'USER_RES', 5);
INSERT INTO `kernel_link_bu2user` VALUES (22, 'USER_RES', 6);
INSERT INTO `kernel_link_bu2user` VALUES (23, 'USER_RES', 7);
INSERT INTO `kernel_link_bu2user` VALUES (24, 'USER_RES', 8);
INSERT INTO `kernel_link_bu2user` VALUES (25, 'USER_RES', 9);
INSERT INTO `kernel_link_bu2user` VALUES (26, 'USER_RES', 10);
INSERT INTO `kernel_link_bu2user` VALUES (27, 'USER_RES', 11);
INSERT INTO `kernel_link_bu2user` VALUES (28, 'USER_RES', 12);

INSERT INTO `module_minimail_from` VALUES (1, 1, 'Bienvenue !', 'Bonjour, et bienvenue au **personnel** sur Iconito.\r\n\r\nCe message est envoy� en guise de test, pour vous permetter de d�couvrir la fonctionnalit� "Minimail". Vous pouvez simplement y r�pondre en cliquant sur le bouton vert.\r\n\r\nPour en savoir plus sur Iconito, n''h�sitez pas � consulter le site [[http://www.iconito.fr|www.iconito.fr]].', 'dokuwiki', NOW(), NULL, NULL, NULL, 0);
INSERT INTO `module_minimail_from` VALUES (2, 1, 'Bienvenue !', 'Bonjour, et bienvenue aux **parents** sur Iconito.\r\n\r\nCe message est envoy� en guise de test, pour vous permetter de d�couvrir la fonctionnalit� "Minimail". Vous pouvez simplement y r�pondre en cliquant sur le bouton vert.\r\n\r\nPour en savoir plus sur Iconito, n''h�sitez pas � consulter le site [[http://www.iconito.fr|www.iconito.fr]].', 'dokuwiki', NOW(), NULL, NULL, NULL, 0);
INSERT INTO `module_minimail_from` VALUES (3, 13, 'Bonjour les enfants !', 'Bonjour les enfants,\r\n\r\nJe suis Martine Meyer, votre institutrice de votre classe (CP bleu). Je vous envoie ce message pour vous souhaiter la bienvenue sur **Iconito**. Amusez-vous bien mais n\'oubliez pas non plus de faire vos devoirs !', 'dokuwiki', NOW(), NULL, NULL, NULL, 0);
INSERT INTO `module_minimail_from` VALUES (4, 1, 'Salut Martine', 'Salut Martine, comment tu vas ? Tu t\'en sors avec Iconito ?', 'dokuwiki', NOW(), NULL, NULL, NULL, 0);

INSERT INTO `module_minimail_to` VALUES (1, 1, 12, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (2, 1, 13, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (3, 1, 14, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (4, 1, 15, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (5, 1, 16, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (6, 2, 17, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (7, 2, 18, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (8, 2, 19, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (9, 3, 20, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (10, 2, 21, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (11, 2, 22, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (12, 2, 23, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (13, 2, 24, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (14, 2, 25, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (15, 2, 26, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (16, 2, 27, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (17, 2, 28, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (18, 3, 2, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (19, 3, 3, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (20, 3, 4, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (21, 3, 6, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (22, 3, 8, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (23, 4, 13, '0000-00-00 00:00:00', 0, 0, 0);

INSERT INTO `kernel_mod_enabled` (`node_type`, `node_id`, `module_type`, `module_id`) VALUES
('BU_VILLE', 1, 'MOD_TELEPROCEDURES', 1);
INSERT INTO `module_teleprocedure` (`id`, `titre`, `date_creation`) VALUES
(1, 'Granville', '2009-01-26 10:26:51');
INSERT INTO `module_teleprocedure_type` (`idtype`, `nom`, `is_online`, `teleprocedure`, `format`, `texte_defaut`, `responsables`, `lecteurs`, mail_from, mail_to, mail_cc, mail_message) VALUES
(1, 'Bris de vitre', 1, 1, 'fckeditor', '<p>Nombre de vitres cass&eacute;es :</p>\r\n<p>Dimensions des vitres :</p>', 'mbraton', '', 'nobody@iconito.fr', 'service@grandville.fr', NULL, 'Voil� une demande � traiter, merci.');
INSERT INTO `module_teleprocedure_type_droit` (`idtype`, `user_type`, `user_id`, `droit`) VALUES
(1, 'USER_VIL', 5, 30);
INSERT INTO `module_teleprocedure_intervention` (`idinter`, `iduser`, `dateinter`, `idetabliss`, `objet`, `idtype`, `idstatu`, `datederniere`, `detail`, `responsables`, `lecteurs`, mail_from, mail_to, mail_cc, mail_message) VALUES
(1, 12, '2009-01-26', 1, 'Vitres bris�es au gymnase', 1, 1, '2009-01-26 12:15:19', '<p>Nombre de vitres cass&eacute;es : 1</p>\r\n<p>Dimensions des vitres : 100x200cm</p>\r\n<p>C''est au gymnase, un ballon&nbsp;a heurt&eacute; la fen&ecirc;tre</p>', 'mbraton', '', 'nobody@iconito.fr', 'service@grandville.fr', NULL, 'Voil� une demande � traiter, merci.');
INSERT INTO `module_teleprocedure_intervention_droit` (`idinter`, `user_type`, `user_id`, `droit`) VALUES
(1, 'USER_VIL', 5, 30);

INSERT INTO `module_carnet_topics` VALUES (1, 'Pr�paration f�te de l\'�cole', 'La f�te de l''�cole aura lieu **jeudi 15 d�cembre 2007 � 16h**. L''�cole est heureuse de f�ter en m�me temps ses 30 ans. A cette occasion, le maire viendra saluer l''�quipe �ducative, les parents et les enfants.\r\n\r\n{{ demo/fete-ecole-1.jpg}}\r\n\r\nUn stand de restauration sera propos�, de m�me que des stands de jeux. Les parents qui souhaitent nous aider sont les bienvenus, merci de nous signaler ce qui vous int�resse:\r\n\r\n    * aider � pr�rarer les salles\r\n    * tenir le stand restauration\r\n    * tenir les stands de jeux (jeu de massacre, poisson vole, finis ton assiette, loups garous)\r\n    * aider � nettoyer et d�monter\r\n    * pr�parer g�teaux ou rapporter boissons\r\n\r\nA bient�t', 'dokuwiki', 13, 1, DATE_ADD(NOW(), INTERVAL -10 MINUTE));
INSERT INTO `module_carnet_topics` VALUES (2, 'Comportement de Lucie', 'Bonjour,\r\n\r\nLucie a fait beaucoup de progr�s au niveau de son attention en classe. Toutefois, elle est tr�s dissip�e lorsqu''elle est assise � c�t� d''Ir�ne. Je les ai s�par�es pour quelques temps.', 'dokuwiki', 13, 1, DATE_ADD(NOW(), INTERVAL -15 MINUTE));
INSERT INTO `module_carnet_topics` VALUES (3, 'Th��tre de marionnettes', 'Le groupe Les Doigts Malins pr�sentera son dernier spectacle de marionnettes le **22 mars � 16h � l''Ill-aux-herbes**.\r\n\r\nCo�t du spectacle: 3 euros (la coop�rative prend en charge le compl�ment de 5 euros).', 'dokuwiki', 13, 1, DATE_ADD(NOW(), INTERVAL -20 MINUTE));

INSERT INTO `module_carnet_messages` VALUES (1, 1, 1, 'Moi je suis pr�t � apporter des boissons, je travaille chez Coca.', 'dokuwiki', 17, DATE_ADD(NOW(), INTERVAL -1 MINUTE));
INSERT INTO `module_carnet_messages` VALUES (2, 2, 2, 'Merci de m''avoir alert� sur le comportement de Lucie. Nous allons lui parler entre quatre z''yeux et j''esp�re qu''elle ne posera plus de probl�mes.\r\n\r\nCordialement, Thierry Lany (papa de Lucie)', 'dokuwiki', 19, DATE_ADD(NOW(), INTERVAL -2 MINUTE));

INSERT INTO `module_carnet_topics_to` VALUES (1, 1);
INSERT INTO `module_carnet_topics_to` VALUES (1, 2);
INSERT INTO `module_carnet_topics_to` VALUES (1, 3);
INSERT INTO `module_carnet_topics_to` VALUES (1, 5);
INSERT INTO `module_carnet_topics_to` VALUES (1, 7);
INSERT INTO `module_carnet_topics_to` VALUES (2, 2);
INSERT INTO `module_carnet_topics_to` VALUES (3, 1);
INSERT INTO `module_carnet_topics_to` VALUES (3, 2);
INSERT INTO `module_carnet_topics_to` VALUES (3, 3);
INSERT INTO `module_carnet_topics_to` VALUES (3, 5);
INSERT INTO `module_carnet_topics_to` VALUES (3, 7);

INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'1', 'prefs', 'avatar', 'admin.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'16', 'prefs', 'avatar', 'mbraton.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'2', 'prefs', 'avatar', 'jean.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'12', 'prefs', 'avatar', 'pfranc.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'13', 'prefs', 'avatar', 'mmeyer.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'18', 'prefs', 'avatar', 'mlenaick.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'17', 'prefs', 'avatar', 'alenaick.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'4', 'prefs', 'avatar', 'marc.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'8', 'prefs', 'avatar', 'jules.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'6', 'prefs', 'avatar', 'irene.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'3', 'prefs', 'avatar', 'lucie.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'15', 'prefs', 'avatar', 'fgilet.png' );
INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'14', 'prefs', 'avatar', 'dkutz.png' );

INSERT INTO `module_groupe_groupe` VALUES (1, 'Les volcans', 'Groupe de travail sur les volcans : d�bats, photos, discussions...', 1, 13, DATE_ADD(NOW(), INTERVAL -55 MINUTE));
INSERT INTO `kernel_link_groupe2node` VALUES (1, 'BU_CLASSE', 1);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ENS', 2, 'CLUB', 1, 70, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ELE', 1, 'CLUB', 1, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ELE', 2, 'CLUB', 1, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ELE', 3, 'CLUB', 1, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ELE', 4, 'CLUB', 1, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ELE', 5, 'CLUB', 1, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ELE', 6, 'CLUB', 1, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ELE', 7, 'CLUB', 1, 35, NULL, NULL);

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 1, 'MOD_ALBUM', 1);
INSERT INTO `module_album_albums` VALUES (1, '', 'aa', DATE_ADD(NOW(), INTERVAL -1 MINUTE), 'be8550b87c', 0);
INSERT INTO `module_album_photos` VALUES (2, 1, 0, 'volcan_007.jpg', 'Une fois solidifi�es, les projections dessinent un paysage de science fiction', NOW(), 'jpg', '96c16abb19', NULL);
INSERT INTO `module_album_photos` VALUES (3, 1, 0, 'volcan_005.jpg', 'Rien ne peut resister � des coul�es de lave',  NOW(), 'jpg', '578c089900', NULL);
INSERT INTO `module_album_photos` VALUES (4, 1, 0, 'volcan_002.jpg', '',  NOW(), 'jpg', '80cac360e5', NULL);
INSERT INTO `module_album_photos` VALUES (5, 1, 0, 'volcan_004.jpg', '',  NOW(), 'jpg', '2b7c5fe806', NULL);
INSERT INTO `module_album_photos` VALUES (6, 1, 0, 'volcan_006.jpg', '',  NOW(), 'jpg', '2d6d81911a', NULL);
INSERT INTO `module_album_photos` VALUES (7, 1, 0, 'Volcans-Volcanos-2954.jpg', '',  NOW(), 'jpg', 'c4055b423d', NULL);
INSERT INTO `module_album_photos` VALUES (8, 1, 0, 'Volcans-Volcanos-3625.jpg', '',  NOW(), 'jpg', '5dc99647a0', NULL);
INSERT INTO `module_album_photos` VALUES (9, 1, 0, '696px-Spaccato_vulcano_ita.png', '', NOW(), 'png', '02213ee64e', NULL);

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 1, 'MOD_BLOG', 1);
INSERT INTO `module_blog` VALUES (1, 'Les volcans', 1, '1_semeru_panache.png', 'Les_volcans', 0, 1, 1, 'POST', 'dokuwiki', NULL);
INSERT INTO `module_blog_articlecategory` VALUES (1, 1, 1, 'G�n�ral', 'General20061023151649');
INSERT INTO `module_blog_articlecategory` VALUES (2, 1, 2, 'Photos', 'Photos20061026163749');
INSERT INTO `module_blog_article` VALUES (1, 1, 'Photos de volcans', 'Voici quelques photos de volcans en activit�.\r\n\r\n[[<PATH>static/album/1_be8550b87c/3_578c089900.jpg|{{ <PATH>static/album/1_be8550b87c/3_578c089900_240.jpg |volcan_005.jpg}}]]\r\n\r\n[[<PATH>static/album/1_be8550b87c/6_2d6d81911a.jpg|{{ <PATH>static/album/1_be8550b87c/6_2d6d81911a_240.jpg |volcan_006.jpg}}]]\r\n\r\n[[<PATH>static/album/1_be8550b87c/4_80cac360e5.jpg|{{ <PATH>static/album/1_be8550b87c/4_80cac360e5_240.jpg |volcan_002.jpg}}]]\r\n\r\n[[<PATH>static/album/1_be8550b87c/5_2b7c5fe806.jpg|{{ <PATH>static/album/1_be8550b87c/5_2b7c5fe806_240.jpg |volcan_004.jpg}}]]', '\n<p>\nVoici quelques photos de volcans en activit�.\n</p>\n\n<p>\n<a href="<PATH>static/album/1_be8550b87c/3_578c089900.jpg" class="media" target="_blank" title="<PATH>static/album/1_be8550b87c/3_578c089900.jpg"  rel="nofollow"><img src="<PATH>static/album/1_be8550b87c/3_578c089900_240.jpg" class="mediacenter" title="volcan_005.jpg" alt="volcan_005.jpg" /></a>\n</p>\n\n<p>\n<a href="<PATH>static/album/1_be8550b87c/6_2d6d81911a.jpg" class="media" target="_blank" title="<PATH>static/album/1_be8550b87c/6_2d6d81911a.jpg"  rel="nofollow"><img src="<PATH>static/album/1_be8550b87c/6_2d6d81911a_240.jpg" class="mediacenter" title="volcan_006.jpg" alt="volcan_006.jpg" /></a>\n</p>\n\n<p>\n<a href="<PATH>static/album/1_be8550b87c/4_80cac360e5.jpg" class="media" target="_blank" title="<PATH>static/album/1_be8550b87c/4_80cac360e5.jpg"  rel="nofollow"><img src="<PATH>static/album/1_be8550b87c/4_80cac360e5_240.jpg" class="mediacenter" title="volcan_002.jpg" alt="volcan_002.jpg" /></a>\n</p>\n\n<p>\n<a href="<PATH>static/album/1_be8550b87c/5_2b7c5fe806.jpg" class="media" target="_blank" title="<PATH>static/album/1_be8550b87c/5_2b7c5fe806.jpg"  rel="nofollow"><img src="<PATH>static/album/1_be8550b87c/5_2b7c5fe806_240.jpg" class="mediacenter" title="volcan_004.jpg" alt="volcan_004.jpg" /></a>\n\n</p>\n', '', '', 'dokuwiki', 13, DATE_FORMAT(NOW(),'%Y%m%d'), DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -10 MINUTE),'%H%i'), '1-Photos_de_volcans', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (1, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (1, 2);
INSERT INTO `module_blog_article` VALUES (2, 1, 'Un volcan, comment �a marche ?', '[[<PATH>static/album/1_be8550b87c/9_02213ee64e.png|{{ <PATH>static/album/1_be8550b87c/9_02213ee64e_240.png|696px-Spaccato_vulcano_ita.png}}]]\r\nUn volcan est un relief terrestre, sous-marin ou extra-terrestre form� � la suite de l''�jection et de l''empilement de mat�riaux issus du manteau (sous forme de laves, cendres, etc). L''accumulation peut atteindre des milliers de m�tres d''�paisseur formant ainsi des montagnes ou des �les. La nature des mat�riaux, le type d''�ruption, leur fr�quence et l''orogen�se donnent aux volcans des formes vari�es mais prenant en g�n�ral l''aspect d''une montagne conique couronn�e par un crat�re ou une caldeira.\r\n\r\nLe lieu principal de sortie des mat�riaux lors d''une �ruption se situe dans la plupart des cas au sommet du volcan, l� o� d�bouche la chemin�e volcanique, mais il arrive que des ouvertures lat�rales apparaissent sur les flancs ou aux pieds du volcan.\r\n\r\nDeux grands types de volcans existent sur terre :\r\n  * les � **volcans rouges** � aux �ruptions effusives relativement calmes et �mettant des laves fluides sous la forme de coul�es. Ce sont les volcans de � point chaud � et les volcans sous-marins des dorsales oc�aniques.\r\n  * les � **volcans gris** � aux �ruptions explosives et �mettant des laves p�teuses et des cendres sous la forme de nu�es ardentes (ou coul�es pyroclastiques) et de panaches volcaniques. Ils sont principalement associ�s au ph�nom�ne de subduction (par exemple les volcans de la � ceinture de feu du Pacifique �).\r\n\r\nOn compte environ 1 500 volcans terrestres actifs dont une soixantaine en �ruption par an. Les volcans sous-marins sont bien plus nombreux.\r\n\r\nLe volcanisme est l''ensemble des ph�nom�nes associ�s aux volcans et � la pr�sence de magma. La volcanologie (ou vulcanologie) est la science de l''�tude, de l''observation et de la pr�vention des risques des volcans.\r\n\r\nLe mot � volcan � tire son origine de Vulcano, une des �les �oliennes nomm�e en l''honneur de Vulcain, le dieu romain du feu. Son �quivalent dans le panth�on grec est H�pha�stos.\r\n\r\nPour en savoir plus, je vous invite � lire l''article complet sur Wikip�dia : [[http://fr.wikipedia.org/wiki/Volcan]]', '\n<p>\n<a href="<PATH>static/album/1_be8550b87c/9_02213ee64e.png" class="media" target="_blank" title="<PATH>static/album/1_be8550b87c/9_02213ee64e.png"  rel="nofollow"><img src="<PATH>static/album/1_be8550b87c/9_02213ee64e_240.png" class="mediaright" align="right" title="696px-Spaccato_vulcano_ita.png" alt="696px-Spaccato_vulcano_ita.png" /></a>\nUn volcan est un relief terrestre, sous-marin ou extra-terrestre form� � la suite de l&#039;�jection et de l&#039;empilement de mat�riaux issus du manteau (sous forme de laves, cendres, etc). L&#039;accumulation peut atteindre des milliers de m�tres d&#039;�paisseur formant ainsi des montagnes ou des �les. La nature des mat�riaux, le type d&#039;�ruption, leur fr�quence et l&#039;orogen�se donnent aux volcans des formes vari�es mais prenant en g�n�ral l&#039;aspect d&#039;une montagne conique couronn�e par un crat�re ou une caldeira.\n</p>\n\n<p>\nLe lieu principal de sortie des mat�riaux lors d&#039;une �ruption se situe dans la plupart des cas au sommet du volcan, l� o� d�bouche la chemin�e volcanique, mais il arrive que des ouvertures lat�rales apparaissent sur les flancs ou aux pieds du volcan.\n</p>\n\n<p>\nDeux grands types de volcans existent sur terre :\n</p>\n<ul>\n<li class="level1"><div class="li"> les � <strong>volcans rouges</strong> � aux �ruptions effusives relativement calmes et �mettant des laves fluides sous la forme de coul�es. Ce sont les volcans de � point chaud � et les volcans sous-marins des dorsales oc�aniques.</div>\n</li>\n<li class="level1"><div class="li"> les � <strong>volcans gris</strong> � aux �ruptions explosives et �mettant des laves p�teuses et des cendres sous la forme de nu�es ardentes (ou coul�es pyroclastiques) et de panaches volcaniques. Ils sont principalement associ�s au ph�nom�ne de subduction (par exemple les volcans de la � ceinture de feu du Pacifique �).</div>\n</li>\n</ul>\n\n<p>\n\nOn compte environ 1 500 volcans terrestres actifs dont une soixantaine en �ruption par an. Les volcans sous-marins sont bien plus nombreux.\n</p>\n\n<p>\nLe volcanisme est l&#039;ensemble des ph�nom�nes associ�s aux volcans et � la pr�sence de magma. La volcanologie (ou vulcanologie) est la science de l&#039;�tude, de l&#039;observation et de la pr�vention des risques des volcans.\n</p>\n\n<p>\nLe mot � volcan � tire son origine de Vulcano, une des �les �oliennes nomm�e en l&#039;honneur de Vulcain, le dieu romain du feu. Son �quivalent dans le panth�on grec est H�pha�stos.\n</p>\n\n<p>\nPour en savoir plus, je vous invite � lire l&#039;article complet sur Wikip�dia : <a href="http://fr.wikipedia.org/wiki/Volcan" class="urlextern" target="_blank" title="http://fr.wikipedia.org/wiki/Volcan"  rel="nofollow">http://fr.wikipedia.org/wiki/Volcan</a>\n\n</p>\n', '', '', 'dokuwiki', 13, DATE_FORMAT(NOW(),'%Y%m%d'), DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -20 MINUTE),'%H%i'), '2-Un_volcan_comment_ca_marche', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (2, 1);
INSERT INTO `module_blog_articlecomment` VALUES (1, 1, 2, 'Jean Lenaick', '', '', '127.0.0.1', DATE_FORMAT(NOW(),'%Y%m%d'), DATE_FORMAT(NOW(),'%H%i'), 'J''adore la deuxi�me !', 1);
INSERT INTO `module_blog_link` VALUES (1, 1, 1, 'Volcan sur Wikip�dia', 'http://fr.wikipedia.org/wiki/Volcan');
INSERT INTO `module_blog_link` VALUES (2, 1, 2, 'Vulcania', 'http://www.vulcania.fr');

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 1, 'MOD_FORUM', 1);
INSERT INTO `module_forum_forums` VALUES (1, '', DATE_ADD(NOW(), INTERVAL -30 MINUTE));
INSERT INTO `module_forum_topics` VALUES (1, 'Qui a d�j� vu un volcan ?', 1, DATE_ADD(NOW(), INTERVAL -25 MINUTE), 13, 2, 0, 1, 2, 2, DATE_ADD(NOW(), INTERVAL -5 MINUTE));
INSERT INTO `module_forum_messages` VALUES (1, 1, 1, 13, 'Les enfants, avez-vous d�j� vu un volcan ?\r\nDites-moi si vous avez vu des images de volcan en activit� � la t�l�, ou sur Internet. Ou peut-�tre avez-vous d�j� vu un vrai volcan !\r\n\r\nQuelles sont vos impressions sur ce ph�nom�ne, cela vous fait-il peur ?', 'dokuwiki', DATE_ADD(NOW(), INTERVAL -25 MINUTE), 1, 0);
INSERT INTO `module_forum_messages` VALUES (2, 1, 1, 2, 'J''ai juste vu des images � la t�l�, et �a fait peur, �a a l''air tr�s tr�s chaud, �a br�le tout ! Et surtout, je crois qu''on ne peut pas l''arr�ter', 'dokuwiki', DATE_ADD(NOW(), INTERVAL -5 MINUTE), 1, 0);
        
INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 1, 'MOD_LISTE', 1);
INSERT INTO `module_liste_listes` VALUES (1, '', DATE_ADD(NOW(), INTERVAL -1 MINUTE));
INSERT INTO `module_liste_messages` VALUES (1, 1, 'Nouvel article sur le blog', 'Je vous �cris pour vous signaler que j''ai r�dig� un nouvel article expliquant le fonctionnement des volcans.\r\nPour lire l''article, cliquez sur le lien suivant :\r\n[lire l''article|index.php?module=blog&desc=default&action=default&blog=Les_volcans]\r\n\r\nBonne lecture !', NOW(), 13);
INSERT INTO `module_minimail_from` VALUES (5, 13, 'Nouvel article sur le blog', 'Je vous �cris pour vous signaler que j''ai r�dig� un nouvel article expliquant le fonctionnement des volcans. Pour lire l''article, cliquez sur le lien suivant : [[index.php?module=blog&desc=default&action=default&blog=Les_volcans|lire l''article]].\r\n\r\nBonne lecture !\n\n----\nInformation : ce message a �t� envoy� par l''interm�diaire du module "Liste de diffusion" du groupe de travail [[index.php?module=groupe&desc=default&action=go&id=1|Les volcans]] dont vous faites partie.', 'dokuwiki', NOW(), NULL, NULL, NULL, 0);
INSERT INTO `module_minimail_to` VALUES (24, 5, 18, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (25, 5, 2, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (26, 5, 3, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (27, 5, 4, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (28, 5, 5, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (29, 5, 6, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (30, 5, 7, '0000-00-00 00:00:00', 0, 0, 0);
INSERT INTO `module_minimail_to` VALUES (31, 5, 8, '0000-00-00 00:00:00', 0, 0, 0);

INSERT INTO `kernel_mod_enabled` VALUES ('BU_ECOLE', 1, 'MOD_BLOG', 2);
INSERT INTO `module_blog` VALUES (2, 'Bienvenue sur le site de l''�cole du Bois Fleuri', 1, '2_ecole.jpg', 'Ecole_du_bois_fleuri', 0, 1, 0, 'POST', 'dokuwiki', NULL);
INSERT INTO `module_blog_articlecategory` VALUES (3, 2, 1, 'Infos pratiques', 'Infos_pratiques');
INSERT INTO `module_blog_articlecategory` VALUES (4, 2, 2, 'Actualit�s de l''�cole', 'Actualites_de_l_ecole');
INSERT INTO `module_blog_article` VALUES (3, 2, 'S�curit� routi�re', 'En compl�ment du travail sur le th�me de la route assur� par Mme RIGOULET, M. RIBOT, intervenant S�curit� Routi�re interviendra aupr�s des classes de CE2, CM1 et CM2 le **vendredi prochain** en salle Roger Chonwa selon le planning ci-dessous :\r\n\r\n^ horaire ^ classe ^ enseignants accompagnateurs ^ \r\n| 07 h 50 � 08 h 45 | CE2 | Mme Doroth�e KUTZ  |\r\n| 08 h 45 � 09 h 40 | CM1 | M. Franck GILET  |\r\n| 09 h 55 � 10 h 50 | CM2 | M. Roger Kyzu  |\r\n\r\n\r\n', '\n<p>\nEn compl�ment du travail sur le th�me de la route assur� par Mme RIGOULET, M. RIBOT, intervenant S�curit� Routi�re interviendra aupr�s des classes de CE2, CM1 et CM2 le <strong>vendredi prochain</strong> en salle Roger Chonwa selon le planning ci-dessous :\n\n</p>\n<table class="inline">\n	<tr class="row0">\n		<th class="col0"> horaire </th><th class="col1"> classe </th><th class="col2"> enseignants accompagnateurs </th>\n	</tr>\n	<tr class="row1">\n		<td class="col0"> 07 h 50 � 08 h 45 </td><td class="col1"> CE2 </td><td class="col2 leftalign"> Mme Doroth�e KUTZ  </td>\n	</tr>\n	<tr class="row2">\n		<td class="col0"> 08 h 45 � 09 h 40 </td><td class="col1"> CM1 </td><td class="col2 leftalign"> M. Franck GILET  </td>\n	</tr>\n	<tr class="row3">\n		<td class="col0"> 09 h 55 � 10 h 50 </td><td class="col1"> CM2 </td><td class="col2 leftalign"> M. Roger Kyzu  </td>\n	</tr>\n</table>\n', '', '', 'dokuwiki', 12, DATE_FORMAT(NOW(),'%Y%m%d'), '0849', '3-Securite_routiere', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (3, 4);
INSERT INTO `module_blog_article` VALUES (4, 2, 'Les horaires de l''�cole', '^ Jours ^ Matin ^ Apr�s-Midi ^\r\n| Lundi | 8h00 � 11h30 | 13h30 � 16h00 |\r\n| Mardi | 8h00 � 11h30 | 13h30 � 16h00 |\r\n| Jeudi | 8h00 � 11h30 | 13h30 � 16h00 |\r\n| Vendredi | 8h00 � 11h30 | 13h30 � 16h00 |', '<table class="inline">\n	<tr class="row0">\n		<th class="col0"> Jours </th><th class="col1"> Matin </th><th class="col2"> Apr�s-Midi </th>\n	</tr>\n	<tr class="row1">\n		<td class="col0"> Lundi </td><td class="col1"> 8h00 � 11h30 </td><td class="col2"> 13h30 � 16h00 </td>\n	</tr>\n	<tr class="row2">\n		<td class="col0"> Mardi </td><td class="col1"> 8h00 � 11h30 </td><td class="col2"> 13h30 � 16h00 </td>\n	</tr>\n	<tr class="row3">\n		<td class="col0"> Jeudi </td><td class="col1"> 8h00 � 11h30 </td><td class="col2"> 13h30 � 16h00 </td>\n	</tr>\n	<tr class="row4">\n		<td class="col0"> Vendredi </td><td class="col1"> 8h00 � 11h30 </td><td class="col2"> 13h30 � 16h00 </td>\n	</tr>\n</table>\n', '', '', 'dokuwiki', 12, DATE_FORMAT(NOW(),'%Y%m%d'), '0820', '4-Les_horaires_de_l_ecole', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (4, 3);
INSERT INTO `module_blog_article` VALUES (5, 2, 'Coordonn�es de l''�cole', 'Notre adresse:\r\n  * 35 avenue truc\r\n  * 67400 CHONZU\r\n  * T�l 01 54 87 98 78\r\n  * Fax 01 54 78 98 79\r\n  * e-mail boisfleuri@iconito.fr\r\n  * Directeur : Pierre Franc\r\n', '\n<p>\nNotre adresse:\n</p>\n<ul>\n<li class="level1"><div class="li"> 35 avenue truc</div>\n</li>\n<li class="level1"><div class="li"> 67400 CHONZU</div>\n</li>\n<li class="level1"><div class="li"> T�l 01 54 87 98 78</div>\n</li>\n<li class="level1"><div class="li"> Fax 01 54 78 98 79</div>\n</li>\n<li class="level1"><div class="li"> e-mail boisfleuri@iconito.fr</div>\n</li>\n<li class="level1"><div class="li"> Directeur : Pierre Franc</div>\n</li>\n</ul>\n', '', '', 'dokuwiki', 12, DATE_FORMAT(NOW(),'%Y%m%d'), '0748', '5-Coordonnees_de_l_ecole', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (5, 3);
INSERT INTO `module_blog_fluxrss` VALUES (1, 2, 'PrimeTICE', 1, 'http://www.educnet.education.fr/bd/urtic/rss/primtice_rss.xml');
INSERT INTO `module_blog_link` VALUES (3, 2, 1, 'Acad�mie de Versailles', 'http://www.ac-versailles.fr');
INSERT INTO `module_blog_link` VALUES (4, 2, 2, 'Educnet : Portail des TIC pour l''�ducation', 'http://www.educnet.education.fr');

INSERT INTO `kernel_mod_enabled` VALUES ('BU_ECOLE', 1, 'MOD_AGENDA', 1);
INSERT INTO `module_agenda_agenda` VALUES (1, 'Ecole du bois fleuri', 'Ecole du bois fleuri', 30);
INSERT INTO `module_agenda_event` VALUES (3, 1, 'F�te de l''�cole', '', 'Ecole du Bois Fleuri', DATE_FORMAT(NOW(),'%Y%m%d'), '08:00', DATE_FORMAT(NOW(),'%Y%m%d'), '12:00', 0, 0, 0, 0, 0, NULL);
INSERT INTO `module_agenda_event` VALUES (4, 1, 'Vacances de la Toussaint', '', '', '20071026', '', '20071105', '', 1, 0, 0, 0, 0, NULL);
        
INSERT INTO `kernel_mod_enabled` VALUES ('BU_CLASSE', 1, 'MOD_AGENDA', 2);
INSERT INTO `module_agenda_agenda` VALUES (2, 'CP bleu', 'CP bleu', 20);
INSERT INTO `module_agenda_event` VALUES (1, 2, 'Sport', '', 'Gymnase Hugo', DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 1 DAY),'%Y%m%d'), '14:00', DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 1 DAY),'%Y%m%d'), '16:00', 0, 0, 1, 0, 0, DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 10 WEEK),'%Y%m%d'));
INSERT INTO `module_agenda_event` VALUES (2, 2, 'Ferme Fritsch', 'Visite de la ferme Fritsch. Pr�voyez bottes et cir�s !', 'Huttelsheim', DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 DAY),'%Y%m%d'), '08:00', DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 DAY),'%Y%m%d'), '09:00', 1, 0, 0, 0, 0, NULL);
INSERT INTO `module_agenda_lecon` VALUES (1, 2, 'Finir les dessins', DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 1 DAY),'%Y%m%d'));
INSERT INTO `module_agenda_lecon` VALUES (2, 2, 'Apporter des jeux', DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 2 DAY),'%Y%m%d'));


INSERT INTO `module_groupe_groupe` VALUES (2, 'La Tunisie', 'Discussions autour de la Tunisie : ses plages, ses habitants, ses monuments...', 1, 16, DATE_ADD(NOW(), INTERVAL -1 DAY));
INSERT INTO `kernel_link_groupe2node` VALUES (2, 'BU_VILLE', 1);
INSERT INTO `kernel_link_user2node` VALUES ('USER_VIL', 5, 'CLUB', 2, 70, NULL, NULL);

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 2, 'MOD_ALBUM', 2);
INSERT INTO `module_album_albums` VALUES (2, '', 'aa', DATE_ADD(NOW(), INTERVAL -5 MINUTE), 'cf057489c9', 0);
INSERT INTO `module_album_photos` VALUES (10, 2, 0, 'EPSN0007.jpg', '', NOW(), 'jpg', 'bf31503e53', NULL);
INSERT INTO `module_album_photos` VALUES (11, 2, 0, 'EPSN0124.jpg', '', NOW(), 'jpg', '52f0518564', NULL);
INSERT INTO `module_album_photos` VALUES (12, 2, 0, 'EPSN0134.jpg', '', NOW(), 'jpg', '0bd77e356a', NULL);
INSERT INTO `module_album_photos` VALUES (13, 2, 0, 'EPSN0146.jpg', '', NOW(), 'jpg', 'c696a3ce60', NULL);
INSERT INTO `module_album_photos` VALUES (14, 2, 0, 'EPSN0189.jpg', '', NOW(), 'jpg', '58a12ff6df', NULL);
INSERT INTO `module_album_photos` VALUES (15, 2, 0, 'EPSN0191.jpg', '', NOW(), 'jpg', '20c4f6aff7', NULL);
INSERT INTO `module_album_photos` VALUES (16, 2, 0, 'EPSN0202.jpg', '', NOW(), 'jpg', '5431069bb7', NULL);
INSERT INTO `module_album_photos` VALUES (17, 2, 0, 'EPSN0213.jpg', '', NOW(), 'jpg', '1c91b6499a', NULL);
INSERT INTO `module_album_photos` VALUES (18, 2, 0, 'EPSN0242.jpg', '', NOW(), 'jpg', 'd0a20b1ed4', NULL);

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 2, 'MOD_BLOG', 3);
INSERT INTO `module_blog` VALUES (3, 'La Tunisie', 1, NULL, 'La_Tunisie', 0, 1, 0, 'POST', 'dokuwiki', NULL);
INSERT INTO `module_blog_articlecategory` VALUES (5, 3, 1, 'G�n�ral', 'GeneralTunisie');
INSERT INTO `module_blog_article` VALUES (6, 3, 'Mes photos', 'Mes photos, de retour de Tunisie :\r\n\r\n[[<PATH>static/album/2_cf057489c9/10_bf31503e53.jpg|{{<PATH>static/album/2_cf057489c9/10_bf31503e53_240.jpg|EPSN0007.jpg}}]] \r\n [[<PATH>static/album/2_cf057489c9/11_52f0518564.jpg|{{<PATH>static/album/2_cf057489c9/11_52f0518564_240.jpg|EPSN0124.jpg}}]] \r\n[[<PATH>static/album/2_cf057489c9/12_0bd77e356a.jpg|{{<PATH>static/album/2_cf057489c9/12_0bd77e356a_240.jpg|EPSN0134.jpg}}]]\r\n[[<PATH>static/album/2_cf057489c9/13_c696a3ce60.jpg|{{<PATH>static/album/2_cf057489c9/13_c696a3ce60_240.jpg|EPSN0146.jpg}}]]\r\n[[<PATH>static/album/2_cf057489c9/14_58a12ff6df.jpg|{{<PATH>static/album/2_cf057489c9/14_58a12ff6df_240.jpg|EPSN0189.jpg}}]]\r\n[[<PATH>static/album/2_cf057489c9/15_20c4f6aff7.jpg|{{<PATH>static/album/2_cf057489c9/15_20c4f6aff7_240.jpg|EPSN0191.jpg}}]]\r\n[[<PATH>static/album/2_cf057489c9/16_5431069bb7.jpg|{{<PATH>static/album/2_cf057489c9/16_5431069bb7_240.jpg|EPSN0202.jpg}}]]\r\n[[<PATH>static/album/2_cf057489c9/17_1c91b6499a.jpg|{{<PATH>static/album/2_cf057489c9/17_1c91b6499a_240.jpg|EPSN0213.jpg}}]]\r\n[[<PATH>static/album/2_cf057489c9/18_d0a20b1ed4.jpg|{{<PATH>static/album/2_cf057489c9/18_d0a20b1ed4_240.jpg|EPSN0242.jpg}}]]\r\n\r\n', '\n<p>\nMes photos, de retour de Tunisie :\n</p>\n\n<p>\n<a href="<PATH>static/album/2_cf057489c9/10_bf31503e53.jpg" class="media" target="_blank" title="<PATH>static/album/2_cf057489c9/10_bf31503e53.jpg"  rel="nofollow"><img src="<PATH>static/album/2_cf057489c9/10_bf31503e53_240.jpg" class="media" title="EPSN0007.jpg" alt="EPSN0007.jpg" /></a> \n <a href="<PATH>static/album/2_cf057489c9/11_52f0518564.jpg" class="media" target="_blank" title="<PATH>static/album/2_cf057489c9/11_52f0518564.jpg"  rel="nofollow"><img src="<PATH>static/album/2_cf057489c9/11_52f0518564_240.jpg" class="media" title="EPSN0124.jpg" alt="EPSN0124.jpg" /></a> \n<a href="<PATH>static/album/2_cf057489c9/12_0bd77e356a.jpg" class="media" target="_blank" title="<PATH>static/album/2_cf057489c9/12_0bd77e356a.jpg"  rel="nofollow"><img src="<PATH>static/album/2_cf057489c9/12_0bd77e356a_240.jpg" class="media" title="EPSN0134.jpg" alt="EPSN0134.jpg" /></a>\n<a href="<PATH>static/album/2_cf057489c9/13_c696a3ce60.jpg" class="media" target="_blank" title="<PATH>static/album/2_cf057489c9/13_c696a3ce60.jpg"  rel="nofollow"><img src="<PATH>static/album/2_cf057489c9/13_c696a3ce60_240.jpg" class="media" title="EPSN0146.jpg" alt="EPSN0146.jpg" /></a>\n<a href="<PATH>static/album/2_cf057489c9/14_58a12ff6df.jpg" class="media" target="_blank" title="<PATH>static/album/2_cf057489c9/14_58a12ff6df.jpg"  rel="nofollow"><img src="<PATH>static/album/2_cf057489c9/14_58a12ff6df_240.jpg" class="media" title="EPSN0189.jpg" alt="EPSN0189.jpg" /></a>\n<a href="<PATH>static/album/2_cf057489c9/15_20c4f6aff7.jpg" class="media" target="_blank" title="<PATH>static/album/2_cf057489c9/15_20c4f6aff7.jpg"  rel="nofollow"><img src="<PATH>static/album/2_cf057489c9/15_20c4f6aff7_240.jpg" class="media" title="EPSN0191.jpg" alt="EPSN0191.jpg" /></a>\n<a href="<PATH>static/album/2_cf057489c9/16_5431069bb7.jpg" class="media" target="_blank" title="<PATH>static/album/2_cf057489c9/16_5431069bb7.jpg"  rel="nofollow"><img src="<PATH>static/album/2_cf057489c9/16_5431069bb7_240.jpg" class="media" title="EPSN0202.jpg" alt="EPSN0202.jpg" /></a>\n<a href="<PATH>static/album/2_cf057489c9/17_1c91b6499a.jpg" class="media" target="_blank" title="<PATH>static/album/2_cf057489c9/17_1c91b6499a.jpg"  rel="nofollow"><img src="<PATH>static/album/2_cf057489c9/17_1c91b6499a_240.jpg" class="media" title="EPSN0213.jpg" alt="EPSN0213.jpg" /></a>\n<a href="<PATH>static/album/2_cf057489c9/18_d0a20b1ed4.jpg" class="media" target="_blank" title="<PATH>static/album/2_cf057489c9/18_d0a20b1ed4.jpg"  rel="nofollow"><img src="<PATH>static/album/2_cf057489c9/18_d0a20b1ed4_240.jpg" class="media" title="EPSN0242.jpg" alt="EPSN0242.jpg" /></a>\n</p>\n', '', '', 'dokuwiki', 16, DATE_FORMAT(NOW(),'%Y%m%d'), '0800', '6-Mes_photos', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (6, 5);

INSERT INTO `module_groupe_groupe` VALUES (3, 'Discussion sur les chartes Internet', 'Discussions relatives � l''adaptation des chartes du minist�re pour l''acc�s � Internet des �l�ves.', 0, 12, DATE_ADD(NOW(), INTERVAL -1 DAY));
INSERT INTO `kernel_link_groupe2node` VALUES (3, 'BU_ECOLE', 1);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ENS', 1, 'CLUB', 3, 70, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ENS', 2, 'CLUB', 3, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_ENS', 3, 'CLUB', 3, 35, NULL, NULL);
INSERT INTO `kernel_link_user2node` VALUES ('USER_VIL', 5, 'CLUB', 3, 35, NULL, NULL);

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 3, 'MOD_FORUM', 2);
INSERT INTO `module_forum_forums` VALUES (2, '', DATE_ADD(NOW(), INTERVAL -23 HOUR));
INSERT INTO `module_forum_topics` VALUES (2, 'Exp�rience d''adaptation d''une charte', 2, DATE_ADD(NOW(), INTERVAL -60 MINUTE), 12, 1, 1, 1, 3, 12, DATE_ADD(NOW(), INTERVAL -60 MINUTE));
INSERT INTO `module_forum_messages` VALUES (3, 2, 2, 12, 'Nous souhaitons adapter la charte du minist�re, afin notamment d''en faire une version "enfants". Quelqu''un a-t-il d�j� r�alis� une telle op�ration ?\r\n\r\nQuels sont les arguments qu''il faut mettre en avant pour les enfants ? Le notion de propri�t� intellectuelle, m�me si elle n''est pas fondamentale pour la charte d''acc�s, me semble importante. Comment pr�senter cela aux enfants ? ', 'dokuwiki', DATE_ADD(NOW(), INTERVAL -60 MINUTE), 1, 0);
INSERT INTO `module_forum_topics` VALUES (3, 'R�sumer une charte', 2, DATE_ADD(NOW(), INTERVAL -30 MINUTE), 12, 2, 3, 1, 5, 13, DATE_ADD(NOW(), INTERVAL -10 MINUTE));
INSERT INTO `module_forum_messages` VALUES (4, 3, 2, 12, 'Peut-on facilement r�sumer une charte d''acc�s � Internet en 4 ou 5 points ? Que mettre en avant ?\r\n\r\nNous cherchons notamment � afficher un r�sum� sur la porte de la salle informatique. ', 'dokuwiki', DATE_ADD(NOW(), INTERVAL -30 MINUTE), 1, 0);
INSERT INTO `module_forum_messages` VALUES (5, 3, 2, 13, 'Il me semble important de rappeler les r�gles concernant le piratage et donc le droit de la propri�t� intellectuelle. Nous savons bien qu''il ne sera pas facile d''arriver � un piratage 0, � aucun MP3 t�l�charg�, mais il me semble important de l''annoncer clairement et d''expliquer pourquoi il n''est pas l�gal de copier une oeuvre, quelle qu''elle soit.\r\n\r\n> il existe des oeuvres fournies avec une licence autorisant la copie, la diffusion, la modification, etc.\r\n> de m�me "mp3" est un standard de fichiers sonores tr�s utile p�dagogiquement : chants d''oiseaux, extraits musicaux pour �tude, textes lus pour persannes handicap�es, etc...\r\n\r\nLe notion de confidentialit� du mot de passe me semble �galement cruciale. Nous avons d�j� eu le cas de deux �l�ves tr�s copines qui se sont �chang�s leurs mots de passe. Ensuite, elles se sont f�ch�es, et l''une a utilis� l''autre mot de passe pour envoyer un message sous un faux nom... Mais si �a pr�te � sourire, c''est presque un "cas d''�cole" � �tudier en classe, en expliquant pourquoi le mot de passe est important et pourquoi il authentifie la personne qui l''utilise.', 'dokuwiki', DATE_ADD(NOW(), INTERVAL -10 MINUTE), 1, 0);

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 3, 'MOD_LISTE', 2);
INSERT INTO `module_liste_listes` VALUES (2, '', DATE_ADD(NOW(), INTERVAL -2 MINUTE));

INSERT INTO `kernel_mod_enabled` VALUES ('CLUB', 3, 'MOD_MALLE', 1);
INSERT INTO `module_malle_malles` VALUES (1, '', DATE_ADD(NOW(), INTERVAL -2 MINUTE), '9a4ba0cdef');
INSERT INTO `module_malle_files` VALUES (1, 1, 0, 'charteproj.pdf', 'charteproj.pdf', 45935, 'application/pdf', '8a7b30cdea', DATE_ADD(NOW(), INTERVAL -1 MINUTE));

INSERT INTO `kernel_mod_enabled` VALUES ('BU_CLASSE', 1, 'MOD_ALBUM', 3);
INSERT INTO `module_album_albums` VALUES (3, '', 'aa', DATE_ADD(NOW(), INTERVAL -2 MINUTE), 'c996b6cf13', 0);
INSERT INTO `module_album_photos` VALUES (19, 3, 0, 'Un dessin plein de couleurs', '', DATE_ADD(NOW(), INTERVAL -1 MINUTE), 'jpg', 'f250349069', NULL);
INSERT INTO `module_album_photos` VALUES (20, 3, 0, 'Dessin sur le th�me de la famille', '', DATE_ADD(NOW(), INTERVAL -1 MINUTE), 'jpg', '1a7b30cdec', NULL);

INSERT INTO `kernel_mod_enabled` VALUES ('BU_CLASSE', 1, 'MOD_BLOG', 4);
INSERT INTO `module_blog` VALUES (4, 'Journal de classe en ligne', 1, '4_cpbleu.jpg', 'CP_bleu', 0, 1, 1, 'POST', 'dokuwiki', NULL);
INSERT INTO `module_blog_articlecategory` VALUES (6, 4, 1, 'Actualit�s', 'Actualites');
INSERT INTO `module_blog_article` VALUES (7, 4, 'Journal des CP Bleu', 'L''�cole Varlin et le site [[http://www.gommeetgribouillages.fr/Presse/Presse.htm|Gomme et Gribouillages]] nous ont permis de reprendre des extraits du journal de la classe de CM1.\r\n\r\n', '\n<p>\nL&#039;�cole Varlin et le site <a href="http://www.gommeetgribouillages.fr/Presse/Presse.htm" class="urlextern" target="_blank" title="http://www.gommeetgribouillages.fr/Presse/Presse.htm"  rel="nofollow">Gomme et Gribouillages</a> nous ont permis de reprendre des extraits du journal de la classe de CM1.\n</p>\n', '', '', 'dokuwiki', 13, DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -7 DAY),'%Y%m%d'), '0803', '7-Journal_des_CP_Bleu', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (7, 6);
INSERT INTO `module_blog_article` VALUES (8, 4, 'L''�quipe de r�daction', 'Et voici en photo l''�quipe de r�daction de ce journal au grand complet.\r\n\r\n{{demo/redac.gif}}', '\n<p>\nEt voici en photo l&#039;�quipe de r�daction de ce journal au grand complet.\n</p>\n\n<p>\n<a href="demo/redac.gif" class="media" target="_blank" title="demo/redac.gif"><img src="demo/redac.gif" class="media" alt="" /></a>\n\n</p>\n', '', '', 'dokuwiki', 13, DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -6 DAY),'%Y%m%d'), '1022', '8-L_equipe_de_redaction', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (8, 6);
INSERT INTO `module_blog_article` VALUES (9, 4, 'Une nouvelle ma�tresse', 'Apr�s les vacances de f�vrier, nous avons eu une nouvelle ma�tresse qui s''appelle Aur�lie. Elle remplace Mme Ploy� qui est partie en stage � "l''�cole des ma�tresses" pendant trois semaines.\r\n\r\nPendant ce temps, nous avons lu des journaux, fait ce journal, du sport, fabriqu� des maracas, plant� des graines de haricots.', '<p>\nApr�s les vacances de f�vrier, nous avons eu une nouvelle ma�tresse qui s''appelle Aur�lie. Elle remplace Mme Ploy� qui est partie en stage � &quot;l''�cole des ma�tresses&quot; pendant trois semaines.\n</p>\n<p>\nPendant ce temps, nous avons lu des journaux, fait ce journal, du sport, fabriqu� des maracas, plant� des graines de haricots.\n</p>', '', '', 'dokuwiki', 13, DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -5 DAY),'%Y%m%d'), '1108', '9-Une_nouvelle_maitresse', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (9, 6);
INSERT INTO `module_blog_article` VALUES (10, 4, 'Pour avoir des dents en bonne sant�', 'En d�but d''ann�e, deux infirmi�res sont venues dans notre classe � Tremblay en France. Elles sont venues pour nous expliquer comment faire pour avoir de belles dents.\r\n\r\nElles nous ont d''abord montr� un petit film: on y voyait un gar�on qui avait des caries. Les infirmi�res nous ont appris qu''il faut se brosser les dents tous les jours et apr�s chaque repas pour ne pas avoir de caries.\r\n\r\nNous avons aussi appris le nom des dents (voir dessins ci-dessous)\r\n\r\n{{demo/dents.gif}}', '\n<p>\nEn d�but d&#039;ann�e, deux infirmi�res sont venues dans notre classe � Tremblay en France. Elles sont venues pour nous expliquer comment faire pour avoir de belles dents.\n</p>\n\n<p>\nElles nous ont d&#039;abord montr� un petit film: on y voyait un gar�on qui avait des caries. Les infirmi�res nous ont appris qu&#039;il faut se brosser les dents tous les jours et apr�s chaque repas pour ne pas avoir de caries.\n</p>\n\n<p>\nNous avons aussi appris le nom des dents (voir dessins ci-dessous)\n</p>\n\n<p>\n<a href="demo/dents.gif" class="media" target="_blank" title="demo/dents.gif"><img src="demo/dents.gif" class="media" alt="" /></a>\n\n</p>\n', '', '', 'dokuwiki', 13, DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -4 DAY),'%Y%m%d'), '1408', '10-Pour_avoir_des_dents_en_bonne_sante', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (10, 6);
INSERT INTO `module_blog_article` VALUES
(11, 4, 'Concours de dessin', '[[<PATH>static/album/3_c996b6cf13/20_1a7b30cdec.jpg|{{ <PATH>static/album/3_c996b6cf13/20_1a7b30cdec_240.jpg|Dessin%20sur%20le%20th%E8me%20de%20la%20famille}}]]\r\nNotre concours de dessin sur le th�me de la famille est termin�. Et la gagnante est...\r\nMais oui, c''est Aur�lie ! Bravo !\r\nMais nous tenons � f�liciter tous les participants, et vous proposons d''ores et d�j� un nouveau concours, qui pourrait avoir pour th�me les loups.\r\n\r\n\r\n', '\n<p>\n<a href="<PATH>static/album/3_c996b6cf13/20_1a7b30cdec.jpg" class="media" target="_blank" title="<PATH>static/album/3_c996b6cf13/20_1a7b30cdec.jpg"  rel="nofollow"><img src="<PATH>static/album/3_c996b6cf13/20_1a7b30cdec_240.jpg" class="mediaright" align="right" title="Dessin%20sur%20le%20th%E8me%20de%20la%20famille" alt="Dessin%20sur%20le%20th%E8me%20de%20la%20famille" /></a>\nNotre concours de dessin sur le th�me de la famille est termin�. Et la gagnante est&hellip;\nMais oui, c&#039;est Aur�lie ! Bravo !\nMais nous tenons � f�liciter tous les participants, et vous proposons d&#039;ores et d�j� un nouveau concours, qui pourrait avoir pour th�me les loups.\n</p>\n', 'Voici �galement un autre dessin qui nous a �t� donn� par la maternelle mais qui est hors concours:\r\n\r\n{{<PATH>static/album/3_c996b6cf13/19_f250349069_240.jpg|Un%20dessin%20plein%20de%20couleurs}}\r\n\r\n', '\n<p>\nVoici �galement un autre dessin qui nous a �t� donn� par la maternelle mais qui est hors concours:\n</p>\n\n<p>\n<a href="<PATH>static/album/3_c996b6cf13/19_f250349069_240.jpg" class="media" target="_blank" title="<PATH>static/album/3_c996b6cf13/19_f250349069_240.jpg"><img src="<PATH>static/album/3_c996b6cf13/19_f250349069_240.jpg" class="media" title="Un%20dessin%20plein%20de%20couleurs" alt="Un%20dessin%20plein%20de%20couleurs" /></a>\n</p>\n', 'dokuwiki', 13, '20090401', '1549', '11_concours_de_dessin', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (11, 6);
INSERT INTO `module_blog_articlecomment` VALUES (2, 11, 13, 'Arthur', '', '', '127.0.0.1', DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -1 DAY),'%Y%m%d'), '1631', 'Bravo Aur�lie pour ce joli dessin !', 1);
INSERT INTO `module_blog_article` VALUES (12, 4, 'Vacances scolaires', 'Un grand sondage sera bient�t lanc� pour savoir si les parents d''�l�ves souhaitent ou pas passer � la semaine de 4 jours. Rendez-vous ici prochainement !', '\n<p>\nUn grand sondage sera bient�t lanc� pour savoir si les parents d&#039;�l�ves souhaitent ou pas passer � la semaine de 4 jours. Rendez-vous ici prochainement !\n\n</p>\n', '', '', 'dokuwiki', 13, DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -2 DAY),'%Y%m%d'), '0953', '12-Vacances_scolaires', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (12, 6);

-- Accueil
INSERT INTO `module_groupe_groupe` (`id`, `titre`, `description`, `is_open`, `createur`, `date_creation`) VALUES
(4, 'Accueil', 'Groupe destin� � g�rer la page d''accueil', 0, 1, DATE_ADD(NOW(), INTERVAL -1 MINUTE));
INSERT INTO `kernel_link_groupe2node` (`groupe_id`, `node_type`, `node_id`) VALUES
(4, 'ROOT', 0);
INSERT INTO `kernel_link_user2node` (`user_type`, `user_id`, `node_type`, `node_id`, `droit`, `debut`, `fin`) VALUES
('USER_EXT', 1, 'CLUB', 4, 70, NULL, NULL);
INSERT INTO `kernel_mod_enabled` (`node_type`, `node_id`, `module_type`, `module_id`) VALUES
('CLUB', 4, 'MOD_ALBUM', 4),
('CLUB', 4, 'MOD_BLOG', 5),
('CLUB', 4, 'MOD_MALLE', 2);
INSERT INTO `module_album_albums` (`id`, `nom`, `prefs`, `date`, `cle`, `public`) VALUES
(4, 'Accueil', 'aa', DATE_ADD(NOW(), INTERVAL -1 MINUTE), 'b3ce1d6dcb', 0);
INSERT INTO `module_blog` (`id_blog`, `name_blog`, `id_ctpt`, `logo_blog`, `url_blog`, `style_blog_file`, `is_public`, `has_comments_activated`, `type_moderation_comments`, `default_format_articles`, `template`) VALUES
(5, 'Accueil', 1, NULL, 'accueil', 0, 0, 0, 'POST', 'ckeditor', NULL);
INSERT INTO `module_blog_articlecategory` (`id_bacg`, `id_blog`, `order_bacg`, `name_bacg`, `url_bacg`) VALUES
(7, 5, 1, 'Actualit�s', 'actualites20100921103303');
INSERT INTO `module_malle_malles` (`id`, `titre`, `date_creation`, `cle`) VALUES
(2, 'Accueil', DATE_ADD(NOW(), INTERVAL -1 MINUTE), '7cfbb3fbc2');
INSERT INTO `module_album_photos` (`id`, `id_album`, `id_dossier`, `nom`, `commentaire`, `date`, `ext`, `cle`, `public`) VALUES
(21, 4, 0, 'Bo�te', NULL, DATE_ADD(NOW(), INTERVAL -1 MINUTE), 'png', '2c8c12e0c6', NULL);
INSERT INTO `module_blog_page` (`id_bpge`, `id_blog`, `name_bpge`, `content_bpge`, `content_html_bpge`, `format_bpge`, `author_bpge`, `date_bpge`, `url_bpge`, `order_bpge`, `is_online`) VALUES
(1, 5, 'Bienvenue !', '<p>\r\n	<img align="left" alt="Bo%C3%AEte" border="0" src="<PATH>static/album/4_b3ce1d6dcb/21_2c8c12e0c6_240.png" />Vous avez install&eacute; le <strong>jeu d&#39;essai</strong> d&#39;<strong>Iconito Ecole Num&eacute;rique 2010</strong>.</p>\r\n<p>\r\n	Cette page d&#39;accueil est personnalis&eacute;e avec quelques contenus qui ont &eacute;t&eacute; install&eacute;s automatiquement.</p>\r\n<p>\r\n	Pour modifier cette page d&#39;accueil, suivez nos conseils dans le <a href="http://www.iconito.fr">forum d&#39;iconito.fr</a> !</p>\r\n', '<p>\r\n	<img align="left" alt="Bo%C3%AEte" border="0" src="<PATH>static/album/4_b3ce1d6dcb/21_2c8c12e0c6_240.png" />Vous avez install&eacute; le <strong>jeu d&#39;essai</strong> d&#39;<strong>Iconito Ecole Num&eacute;rique 2010</strong>.</p>\r\n<p>\r\n	Cette page d&#39;accueil est personnalis&eacute;e avec quelques contenus qui ont &eacute;t&eacute; install&eacute;s automatiquement.</p>\r\n<p>\r\n	Pour modifier cette page d&#39;accueil, suivez nos conseils dans le <a href="http://www.iconito.fr">forum d&#39;iconito.fr</a> !</p>\r\n', 'ckeditor', 1, DATE_FORMAT(NOW(),'%Y%m%d'), 'bienvenue', 1, 1);
INSERT INTO `module_blog_article` (`id_bact`, `id_blog`, `name_bact`, `sumary_bact`, `sumary_html_bact`, `content_bact`, `content_html_bact`, `format_bact`, `author_bact`, `date_bact`, `time_bact`, `url_bact`, `sticky_bact`, `is_online`) VALUES
(13, 5, 'L''�cole du Bois Fleuri se met � Iconito', '<p>\r\n	A partir d&#39;aujourd&#39;hui, Iconito est d&eacute;ploy&eacute; &agrave; l&#39;&eacute;cole du Bois Fleuri. Gr&acirc;ce notamment &agrave; l&#39;implication du directeur M. Franc et des enseignants, les parents d&#39;&eacute;l&egrave;ves vont pouvoir :</p>\r\n<ul>\r\n	<li>\r\n		suivre les aventures de leurs enfants lors des sorties et des classes vertes</li>\r\n	<li>\r\n		communiquer avec les enseignants</li>\r\n	<li>\r\n		obtenir des informations pratiques sur l&#39;&eacute;cole</li>\r\n</ul>\r\n', '<p>\r\n	A partir d&#39;aujourd&#39;hui, Iconito est d&eacute;ploy&eacute; &agrave; l&#39;&eacute;cole du Bois Fleuri. Gr&acirc;ce notamment &agrave; l&#39;implication du directeur M. Franc et des enseignants, les parents d&#39;&eacute;l&egrave;ves vont pouvoir :</p>\r\n<ul>\r\n	<li>\r\n		suivre les aventures de leurs enfants lors des sorties et des classes vertes</li>\r\n	<li>\r\n		communiquer avec les enseignants</li>\r\n	<li>\r\n		obtenir des informations pratiques sur l&#39;&eacute;cole</li>\r\n</ul>\r\n', '<br />\r\n', '<br />\r\n', 'ckeditor', 1, DATE_FORMAT(NOW(),'%Y%m%d'), '0722', '13_lecole_du_bois_fleuri_se_met_iconito', 0, 1);
INSERT INTO `module_blog_article` (`id_bact`, `id_blog`, `name_bact`, `sumary_bact`, `sumary_html_bact`, `content_bact`, `content_html_bact`, `format_bact`, `author_bact`, `date_bact`, `time_bact`, `url_bact`, `sticky_bact`, `is_online`) VALUES
(14, 5, 'D�marrage du projet', '<p>\r\n	Apr&egrave;s une installation facile et rapide, Iconito est install&eacute; dans notre commune. Les &eacute;coles devraient rapidement s&#39;approprier l&#39;outil et permettre &agrave; tous les acteurs du monde &eacute;ducatif d&#39;en profiter...</p>\r\n', '<p>\r\n	Apr&egrave;s une installation facile et rapide, Iconito est install&eacute; dans notre commune. Les &eacute;coles devraient rapidement s&#39;approprier l&#39;outil et permettre &agrave; tous les acteurs du monde &eacute;ducatif d&#39;en profiter...</p>\r\n', '<br />\r\n', '<br />\r\n', 'ckeditor', 1, DATE_FORMAT(NOW(),'%Y%m%d'), '0709', '14_demarrage_du_projet', 0, 1);








