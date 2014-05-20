<?php


class ZoneDashboardStatistics extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $ppo = new CopixPPO ();
        $toReturn = "";

        // Si le module de statistiques est activé, on rend le template
        if ((bool)CopixConfig::get('statistiques|enabled')) {
            $toReturn = $this->_usePPO ($ppo, 'dashboardStatistics.tpl');
        }

        return true;
    }



}
