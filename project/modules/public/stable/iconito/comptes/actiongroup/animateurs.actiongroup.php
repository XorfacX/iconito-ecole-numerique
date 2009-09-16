<?php
/**
* @package  Iconito
* @subpackage Comptes
* @version   $Id: animateurs.actiongroup.php,v 1.1 2009-08-31 10:00:17 fmossmann Exp $
* @author   Fr�d�ric Mossmann
* @copyright 2009 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require_once (COPIX_MODULE_PATH.'kernel/'.COPIX_CLASSES_DIR.'kernel.class.php');

/**
 * @author	Fr�d�ric Mossmann
 */
class ActionGroupAnimateurs extends CopixActionGroup {

	/**
	 * list
	 * 
	 * Affiche le formulaire de modification d'un utilisateur ext�rieur
	 * 
	 * @package	Comptes
	 * @author	Fr�d�ric Mossmann <fmossmann@cap-tic.fr>
	 */
	function getList() {
		if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN )
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_comptes.css"));

		$tpl = & new CopixTpl ();
		$tplGrVilles = & new CopixTpl ();
		
		$userext_dao = & CopixDAOFactory::create("kernel|kernel_ext_user");

		$this->vars['nom']    = trim( $this->vars['nom'] );
		$userext_item = $userext_dao->get( $this->vars['id'] );

		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getuserextadd'));
					
		
		$tpl->assign ('MAIN', $result );
		
		$menu=array();
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.return_getuserext'), 'url' => CopixUrl::get ('comptes||getUserExt') );
		$tpl->assign ('MENU', $menu );
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

}

?>
