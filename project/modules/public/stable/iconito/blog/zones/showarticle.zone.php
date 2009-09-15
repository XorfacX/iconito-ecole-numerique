<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: showarticle.zone.php,v 1.14 2009-01-09 16:06:15 cbeyer Exp $
* @author Vallat C�dric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class ZoneShowArticle extends CopixZone {
   function _createContent (&$toReturn) {

      $tpl  = & new CopixTpl ();

      $blog = $this->getParam('blog', '');
      $comment = $this->getParam('comment', NULL);

      //on r�cup�re l'ensemble des articles du blog
      $dao = CopixDAOFactory::create('blog|blogarticle');

      //$article = $dao->getArticleByUrl($blog->id_blog, $this->getParam('article', ''));
			list($id_bact, ) = explode("-", $this->getParam('article', ''));
      $article = $dao->getArticleById($blog->id_blog, $id_bact);
			

	  if(! $article){
			$toReturn = '{/}'.$tpl->fetch('showarticle.tpl');
      return true;
	  }

      //encodage des URL des cat�gories pour caract�res sp�ciaux
			if (is_array($article->categories)) {
	    	foreach($article->categories as $key=>$categorie){
  	  			$article->categories[$key]->url_bacg = urlencode($categorie->url_bacg);
	    	}
			}

      $tpl->assign ('article', $article);
	    // Recherche de tous les commentaires associ�s � cet article
	    $commentDAO = CopixDAOFactory::create('blog|blogarticlecomment');
	    $res = $commentDAO->findCommentOrderBy($article->id_bact, 1);
	    $listComment = array();
	    foreach($res as $r) {
	      $r->time_bacc = BDToTime($r->time_bacc);
	      array_push($listComment, $r);
	    }
			
		if ($comment) {
		    $toEdit = $comment;
		} else {	// On r�cup�re l'utilisateur connect�
			$user = BlogAuth::getUserInfos();
			$toEdit = CopixDAOFactory::createRecord('blogarticlecomment');
		    $toEdit->authorid_bacc = $user->userId;
	    	$toEdit->authorname_bacc = $user->name;
		    $toEdit->authoremail_bacc = $user->email;
		    $toEdit->authorweb_bacc = $user->web;		
		}

      $tpl->assign ('blog', $blog);
      $tpl->assign ('toEdit', $toEdit);
      $tpl->assign ('listComment', $listComment);
			$tpl->assign ('errors', $this->getParam('errors',null));
			$tpl->assign ('showErrors', $this->getParam('showErrors',false));
 			$tpl->assign ('canComment', BlogAuth::canComment($blog->id_blog));

			$plugStats = & CopixCoordination::getPlugin ('stats');
			$plugStats->setParams(array('objet_a'=>$article->id_bact));

      // retour de la fonction :
      $toReturn = $article->name_bact.'{/}'.$tpl->fetch('showarticle.tpl');
      return true;
   }
}
?>
