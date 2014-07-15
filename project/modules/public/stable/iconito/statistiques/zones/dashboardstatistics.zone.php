<?php


class ZoneDashboardStatistics extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $ppo = new CopixPPO ();
        $toReturn = "";
        $userType = _currentUser()->getExtra("type");
        $groupsDenied = array("USER_ELE", "USER_RES", "USER_EXT");
        
        // Si le module de statistiques est activé, on rend le template
        if ((bool)CopixConfig::get('statistiques|enabled') &&  (!in_array($userType, $groupsDenied) || Kernel::isAdmin()) ) {
            $toReturn = $this->_usePPO ($ppo, 'dashboardStatistics.tpl');
        }

        return true;
    }



}
