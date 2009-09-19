<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: showblogdocument.zone.php,v 1.2 2007-06-01 16:08:43 cbeyer Exp $
* @author	Vallat C�dric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
* Administration pannel
* @param id_head // the current copixheading indice can be null if racine
*/

_classInclude('blog|blogauth');

class ZoneShowBlogDocument extends CopixZone {
	function _createContent (&$toReturn) {
		//Getting the user.
		//Create Services, and DAO
		$tpl = & new CopixTpl ();

		$id_blog = $this->getParam('id_blog', '');
		//capability
		//$tpl->assign ('canManageDocument' , BlogAuth::canMakeInBlog('ADMIN_DOCUMENTS',create_blog_object($id_blog)));
		
		
		// On regarde si le parent a un album photos
		$parent = Kernel::getModParentInfo( "MOD_BLOG", $id_blog);
		//print_r($parent);
		if ($parent) {
		
			$mods = Kernel::getModEnabled ($parent['type'], $parent['id']);
			$mods = Kernel::filterModuleList ($mods, 'MOD_MALLE');
			if ($mods)
				$tpl->assign ('album', $mods[0]->module_id);
		}
		
		$tpl->assign ('id_blog', $id_blog);
		$tpl->assign ('kind', $this->getParam('kind', ''));

		// retour de la fonction :
		$toReturn = $tpl->fetch('blog.show.document.tpl');
		return true;
	}
}
?>
