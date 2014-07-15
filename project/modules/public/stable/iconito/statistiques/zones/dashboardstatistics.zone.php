<?php


class ZoneDashboardStatistics extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $ppo = new CopixPPO ();
        $toReturn = "";
        $userType = _currentUser()->getExtra("type");

        // Si le module de statistiques est activé, on rend le template
        if ((bool)CopixConfig::get('statistiques|enabled') &&  $userType != "USER_ELE" && $userType != "USER_RES") {
            $toReturn = $this->_usePPO ($ppo, 'dashboardStatistics.tpl');
        }

        return true;
    }



}
