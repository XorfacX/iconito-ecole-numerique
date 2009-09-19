<?php

/**
 * Aide du module Minimail
 * 
 * @package Iconito
 * @subpackage	Minimail
 */
class HelpMinimail {

	/**
	 * Les pages de l'aide
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/04/11
	 * @return array Les pages d'aide de la rubrique. Cl�: nom de la page (genre de login). Valeur: tableau, index� avec "name" (login), "title" (titre i18n), "links" (tableau permettant de proposer des liens de type "Voir aussi". Mettre le login de la page ou module|page si c'est une page d'un autre module
	 */
	function getPages () {
		return array (
			'presentation' => array(
				'name'=>'presentation',
				'title'=>CopixI18N::get ('minimail|minimail.help.page.presentation'),
				'links'=>array('write'),
			),
			'write' => array(
				'name'=>'write',
				'title'=>CopixI18N::get ('minimail|minimail.help.page.write'),
			),
		);
	}
}

?>
