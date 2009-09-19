<?php
/**
* @package Iconito
* @subpackage	Blog
* @author	Vallat C�dric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
* Administration pannel
* @param id_head // the current copixheading indice can be null if racine
*/

class ZoneEditBlog extends CopixZone {
	function _createContent (&$toReturn) {
		//Getting the user.
		//Create Services, and DAO
		$tpl = & new CopixTpl ();

		$tpl->assign ('blog', $this->getParam('blog',null));
		$tpl->assign ('kind', $this->getParam('kind',null));
		$tpl->assign ('id_blog', $this->getParam('id_blog', ''));
		$tpl->assign ('errors', $this->getParam('errors', ''));
		$tpl->assign ('showErrors', $this->getParam('showErrors', ''));
		$tpl->assign ('logoPath', $this->getParam('logoPath', null));
		$tpl->assign ('tabBlogFunctions', $this->getParam('tabBlogFunctions', null));

		$tpl->assign ('is_public', array('values'=>array(1,0), 'output'=>array(CopixI18N::get('blog|blog.oui'), CopixI18N::get('blog|blog.non'))));
		$tpl->assign ('has_comments_activated', array('values'=>array(1,0), 'output'=>array(CopixI18N::get('blog|blog.oui'), CopixI18N::get('blog|blog.non'))));
		$tpl->assign ('type_moderation_comments', array('values'=>array('POST','PRE'), 'output'=>array(CopixI18N::get('blog|blog.type_moderation_comments.post'), CopixI18N::get('blog|blog.type_moderation_comments.pre'))));

		$formats = CopixConfig::get ('blog|blog.formats_articles');
		$tabFormats = explode (',',$formats);
		$values = $output = array();
		foreach ($tabFormats as $k) {
			$values[] = $k;
			$output[] = CopixI18N::get('blog|blog.default_format_articles.'.$k);
		}
		$tpl->assign ('default_format_articles', array('values'=>$values, 'output'=>$output));
		$tpl->assign ('logo_max_width', CopixConfig::get ('blog|blog.default.logo_max_width'));
  
		// retour de la fonction :
		$toReturn = $tpl->fetch('blog.edit.tpl');
		return true;
	}
}
?>
