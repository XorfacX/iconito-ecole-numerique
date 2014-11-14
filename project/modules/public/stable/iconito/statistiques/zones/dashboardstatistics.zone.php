<?php


class ZoneDashboardStatistics extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $ppo = new CopixPPO ();
        $toReturn = "";
        $userType = _currentUser()->getExtra("type");
        $groupsDenied = array("USER_ELE", "USER_RES", "USER_EXT");
        $inspecteur_dao = & CopixDAOFactory::create("kernel|kernel_ien");
        $inspecteur = $inspecteur_dao->get(_currentUser()->getExtra("type"), _currentUser()->getExtra("id"));
        $animateur_dao = & CopixDAOFactory::create("kernel|kernel_animateurs");
        $animateur = $animateur_dao->get(_currentUser()->getExtra("type"), _currentUser()->getExtra("id"));
        
       // Si le module de statistiques est activé et que l'utilisateur a les droits d'accès, on rend le template
        if ((bool)CopixConfig::get('statistiques|enabled') &&  (!in_array($userType, $groupsDenied) || Kernel::isAdmin() || $animateur || $inspecteur) ) {
            $toReturn = $this->_usePPO ($ppo, 'dashboardStatistics.tpl');
        }

        return true;
    }



}