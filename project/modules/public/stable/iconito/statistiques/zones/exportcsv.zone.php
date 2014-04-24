<?php

/**
 * @package    Iconito
 * @subpackage Gestionautonome
 * @author     Julien Pottier
 */
class ZoneExportCsv extends CopixZone
{
    /**
     * Affichage des groupes de villes
     */
    public function _createContent(& $toReturn)
    {
        $ppo = new CopixPPO ();

        $ppo->part = $this->getParam('part');
        $ppo->options = $this->getParam('options');

        $toReturn = $this->_usePPO($ppo, '_export_csv.tpl');
    }

}