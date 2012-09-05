<?php

/**
 * Gestion des pr�f�rences du module Minimail
 *
 * @package Iconito
 * @subpackage	Carnet
 */
class ModCarnetPrefs
{
    /**
     * Renvoie les pr�f�rences du module
     *
     * @author Frederic Mossmann <fmossmann@cap-tic.fr>
     * @since 2006/12/01
     * @param array $data (option) Tableau avec les donn�es (venues de la base)
     * @return array Tableau de tableaux avec toutes les pr�f�rences
     */
    public function getPrefs ( $data=null )
    {
        $toReturn = array();


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
    public function checkPrefs( $module, $data )
    {
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
    public function setPrefs( $module, $data )
    {
        if( !isset($data['alerte_carnet']) ) $data['alerte_carnet']=0;
        $pref_service = & CopixClassesFactory::Create ('prefs|prefs');
        $pref_service->setPrefs( $module, $data );
    }

}

