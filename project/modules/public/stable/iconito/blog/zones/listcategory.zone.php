<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: listcategory.zone.php,v 1.8 2007-06-01 16:08:43 cbeyer Exp $
* @author	Vallat C�dric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class ZoneListCategory extends CopixZone {
   function _createContent (&$toReturn) {

      $tpl  = & new CopixTpl ();

      $blog = $this->getParam('blog', '');
      
      $dao = _dao('blog|blogarticlecategory');
      $listCategory = $dao->getAllCategoriesFromBlog($blog->id_blog);
			
			//encodage des URL � cause des caract�res sp�ciaux
      foreach($listCategory as $key=>$category){      		
      		$listCategory[$key]->url_bacg = urlencode($category->url_bacg);      		
      }
      
      $tpl->assign ('listCategory', $listCategory);
      $tpl->assign ('blog' , $blog);


      $toReturn = $tpl->fetch('listcategory.tpl');
      return true;
   }
}
?>
