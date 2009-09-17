<?php

/**
 * Gestion des pr�f�rences du module Minimail
 * 
 * @package Iconito
 * @subpackage	Carnet
 */
class ModCarnetPrefs {

	/**
	 * Renvoie les pr�f�rences du module
	 *
	 * @author Frederic Mossmann <fmossmann@cap-tic.fr>
	 * @since 2006/12/01
	 * @param array $data (option) Tableau avec les donn�es (venues de la base)
	 * @return array Tableau de tableaux avec toutes les pr�f�rences
	 */
	function getPrefs ( $data=null ) {
		$toReturn = array();

    /*
		$toReturn['name'] = 'Cahier de liaison';
		$toReturn['form'] = array(
			array(
				'type'=>'titre',
				'text'=>CopixI18N::get ('carnet|carnet.config.alerte.title'), // Alerte par email
				'expl'=>CopixI18N::get ('carnet|carnet.config.alerte.expl'), // 'Vous pouvez �tre alert� par un email � chaque fois que vous recevez un minimail',
			),
			array(
				'code'=>'alerte_carnet',
				'type'=>'checkbox',
				'text'=>CopixI18N::get ('carnet|carnet.config.alerte.active'),
				'value'=>($data['alerte_carnet']?true:false)
			),
		);
    */
		return( $toReturn );
	}

	/**
	 * V�rifie que les valeurs saisies pour les pr�f�rences sont valides
	 *
	 * @author Frederic Mossmann <fmossmann@cap-tic.fr>
	 * @since 2006/12/01
	 * @param string $module Nom du module
	 * @param array $data Valeurs
	 * @return array Tableau d'erreurs ou tableau vide si pas d'erreurs
	 */
	function checkPrefs( $module, $data ) {
		$error = array();
		return( $error );
	}

	/**
	 * Enregistre les valeurs des pr�f�rences
	 *
	 * @author Frederic Mossmann <fmossmann@cap-tic.fr>
	 * @since 2006/12/01
	 * @param string $module Nom du module
	 * @param array $data Valeurs
	 */
	function setPrefs( $module, $data ) {
		if( !isset($data['alerte_carnet']) ) $data['alerte_carnet']=0;
		$pref_service = & CopixClassesFactory::Create ('prefs|prefs');
		$pref_service->setPrefs( $module, $data );
	}
		
}

?>
