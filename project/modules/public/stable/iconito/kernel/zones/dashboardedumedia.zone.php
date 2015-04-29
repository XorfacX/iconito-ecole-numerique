<?php

/**
 * Pour le dashboard, zone affichant le lien vers l'annuaire des groupes, et le bouton pour creer un groupe
 *
 * @package Iconito
 * @subpackage	Groupe
 */
class ZoneDashboardEdumedia extends CopixZone
{
    /**
     * Pour le dashboard, zone affichant le lien vers l'annuaire des groupes, et le bouton pour creer un groupe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2010/06/11
     */
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();
        //$tpl->assign ('list', $groupes);

        if( !CopixConfig::exists('default|conf_edumedia_rne')) {
            $toReturn = '';
            return true;
        }

        $nodes = Kernel::getNodes();
        $ecole = array_shift($nodes['BU_ECOLE']);
        $ecoleInfo = Kernel::getNodeInfo('BU_ECOLE', $ecole['id'], false);
        $confRNE = CopixConfig::get ('default|conf_edumedia_rne');
        $explodedRNE = explode(',', $confRNE);

        if (!in_array($ecoleInfo['rne'], $explodedRNE)) {
            $toReturn = "";
            return true;
        }

        $service = 'iconito';
        $date = date('dmY');
        $login = 'iconito-limoges-'.$ecoleInfo['rne']; // _currentUser()->getExtra('login');
        $hash = '-EM_ICONITO-';
        $url = 'http://junior.edumedia-sciences.com/fr/?service='.$service.'&time='.$date.'&user='.$login.'&token='.md5($service.$date.$hash.$login);

        $tpl->assign ('url', $url);
        $toReturn = $tpl->fetch('dashboardedumedia.tpl');
        return true;

    }
}
