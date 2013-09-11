<?php

/**
 * @package    Iconito
 * @subpackage Gestionautonome
 * @author     Jérémy Hubert
 */
class ZoneApiRequest extends CopixZone
{
    /**
     * Affichage des groupes de villes
     */
    public function _createContent(& $toReturn)
    {
        $ppo = new CopixPPO ();

        $stat = $this->getParam('stat');
        $filter = $this->getParam('filter');

        if ($stat) {
            $mapping = new ApiMapping;
            $class = $mapping->getClass($stat, $filter);
            $template = $mapping->getTemplate($stat);
            $ppo->requestClass = $class;
            $ppo->filter = $filter;
            $ppo->label = $mapping->getLabel($stat);
        }

        $toReturn = $this->_usePPO($ppo, $template);
    }

}