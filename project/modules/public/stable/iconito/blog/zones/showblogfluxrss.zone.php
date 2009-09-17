<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: showblogfluxrss.zone.php,v 1.2 2007-06-01 16:08:43 cbeyer Exp $
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

class ZoneShowBlogFluxRss extends CopixZone {
	function _createContent (&$toReturn) {
		//Getting the user.
		//Create Services, and DAO
		$tpl = & new CopixTpl ();

		$id_blog = $this->getParam('id_blog', '');
		//capability
		//$tpl->assign ('canManageRss' , BlogAuth::canMakeInBlog('ADMIN_RSS',create_blog_object($id_blog)));

		$tpl->assign ('id_blog', $id_blog);
		$tpl->assign ('kind', $this->getParam('kind', ''));

		// Recherche de tous les liens RSS de la base
		$blogRssDAO = _dao('blog|blogfluxrss');
		$tabRss = $blogRssDAO->findAllOrder($id_blog);

		$tpl->assign ('tabRss', $tabRss);

		// retour de la fonction :
		$toReturn = $tpl->fetch('blog.show.fluxrss.tpl');
		return true;
	}
}
?>
