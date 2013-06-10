ALTER TABLE  `module_quiz_quiz` CHANGE  `lock`  `is_locked` TINYINT( 4 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `module_quiz_questions` CHANGE  `order`  `position` INT( 5 ) UNSIGNED NOT NULL;
ALTER TABLE  `module_quiz_choices` CHANGE  `order`  `position` INT( 5 ) UNSIGNED NOT NULL;