<?php


class ZoneDashboardStatistics extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $ppo = new CopixPPO ();
        $toReturn = "";

        $toReturn = $this->_usePPO ($ppo, 'dashboardStatistics.tpl');
        return true;
    }



}
