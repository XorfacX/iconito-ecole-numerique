-- Augmentation de la taille des champs pour le Blog

ALTER TABLE  `module_blog_article` CHANGE  `sumary_bact`  `sumary_bact` MEDIUMTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ;

ALTER TABLE  `module_blog_article` CHANGE  `sumary_html_bact`  `sumary_html_bact` MEDIUMTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ;

ALTER TABLE  `module_blog_article` CHANGE  `content_bact`  `content_bact` MEDIUMTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ;

ALTER TABLE  `module_blog_article` CHANGE  `content_html_bact`  `content_html_bact` MEDIUMTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL ;