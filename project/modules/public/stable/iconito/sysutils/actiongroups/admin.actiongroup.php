<?php
/**
 * Admin - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Admin
 * @version     $Id: admin.actiongroup.php,v 1.2 2007-03-20 10:53:13 cbeyer Exp $
 * @author      Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

_classInclude('sysutils|admin');

class ActionGroupAdmin extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');
    }

   public function home ()
   {
    if (!Kernel::isAdmin())
          return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back'=>CopixUrl::get ()));

        $tplHome = new CopixTpl();

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('sysutils|admin.moduleDescription'));
        $tpl->assign ('MENU', Admin::getMenu('sysutils'));

        $typeStats = 0; // 0 = old, 1 = new Statistiques
        $userType = _currentUser()->getExtra("type");
        $groupsDenied = array("USER_ELE", "USER_RES", "USER_EXT");
       
        // Si le module de statistiques est activé et que l'utilisateur a les droits d'accès, on rend le template
        if ((bool)CopixConfig::get('statistiques|enabled') &&  (!in_array($userType, $groupsDenied) || Kernel::isAdmin() || $animateur || $inspecteur) ) {
            $typeStats = 1;
        }
        $tplHome->assign('typeStats', $typeStats);
        $tplHome->assign('superadmin', Kernel::isSuperAdmin());
        $tplHome->assign('adminfonctionnel', Kernel::isAdminFonctionnel());
        $tpl->assign ('MAIN', $tplHome->fetch('sysutils|home.tpl'));

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    public function processPhpinfo ()
    {
        $ppo = new CopixPPO ();
        $ppo->TITLE_PAGE = 'PHPInfo';
        $ppo->MENU = Admin::getMenu('phpinfo');
        $ppo->CopixVersion = COPIX_VERSION;

        ob_start();
        phpinfo();
        $info = ob_get_contents();
        ob_end_clean();
        $ppo->phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $info);

        return _arPpo ($ppo, 'phpinfo.tpl');
    }


}
