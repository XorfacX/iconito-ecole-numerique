<?php
/**
* @package	copix
* @version	$Id: blogutils.class.php,v 1.15 2007-06-01 16:08:43 cbeyer Exp $
* @author	C�dric VALLAT, Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * Class de gestion des droits utilisateur
 */

require_once (COPIX_UTILS_PATH.'CopixUtils.lib.php');

/*
    getMenu
    -------------------
    @file 		frontblog.actiongroup.php
    @version 	1.0.0b
    @date 		2010-08-30 14:34:49 +0200 (Mon, 30 Aug 2010)
    @author 	S.HOLTZ <sholtz@cap-tic.fr>
    Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
*/
function getBlogAdminMenu($blog, $action=99)
{
    $id_blog = $blog->id_blog;
    $menu = array();
    $current = false;

        $txt = CopixI18N::get('blog|blog.nav.blog');
        $type = 'read';
        $size = 56;
        $current = ($action==99)? true : false;
        $url = CopixUrl::get ('|', array("blog"=>$blog->url_blog));
        $menu[] = array('txt'=>$txt,'type' => $type, 'size'=> $size, 'current' => $current, 'url' => $url);

    if (BlogAuth::canMakeInBlog('ADMIN_CATEGORIES', $blog)) {
        $txt = CopixI18N::get('blog|blog.nav.categories');
        $type = 'tag';
        $current = ($action==1)? true : false;
        $url = CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>1));
        $menu[] = array('txt'=>$txt,'type' => $type, 'size'=> $size, 'current' => $current, 'url' => $url);
    }

    if (BlogAuth::canMakeInBlog('ADMIN_ARTICLES', $blog)) {
        $txt = CopixI18N::get('blog|blog.nav.articles');
        $type = 'article';
        $current = ($action==0)? true : false;
        $url = CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>0));
        $menu[] = array('txt'=>$txt,'type' => $type, 'size'=> $size, 'current' => $current, 'url' => $url);
    }

    if (BlogAuth::canMakeInBlog('ADMIN_PAGES',$blog)) {
        $txt = CopixI18N::get('blog|blog.nav.pages');
        $type = 'page';
        $current = ($action==5)? true : false;
        $url = CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>5));
        $menu[] = array('txt'=>$txt,'type' => $type, 'size'=> $size, 'current' => $current, 'url' => $url);
    }

    if (BlogAuth::canMakeInBlog('ADMIN_LIENS', $blog)) {
        $txt = CopixI18N::get('blog|blog.nav.links');
        $type = 'link';
        $current = ($action==2)? true : false;
        $url = CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>2));
        $menu[] = array('txt'=>$txt,'type' => $type, 'size'=> $size, 'current' => $current, 'url' => $url);
    }

    if (BlogAuth::canMakeInBlog('ADMIN_RSS',$blog)) {
        $txt = CopixI18N::get('blog|blog.nav.rss');
        $type = 'rss';
        $current = ($action==6)? true : false;
        $url = CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>6));
        $menu[] = array('txt'=>$txt,'type' => $type, 'size'=> $size, 'current' => $current, 'url' => $url);
    }

    if (BlogAuth::canMakeInBlog('ADMIN_OPTIONS',$blog)) {
        $txt = CopixI18N::get('blog|blog.nav.options');
        $type = 'options';
        $current = ($action==4)? true : false;
        $url = CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>4));
        $menu[] = array('txt'=>$txt,'type' => $type, 'size'=> $size, 'current' => $current, 'url' => $url);
    }

    if (BlogAuth::canMakeInBlog("ADMIN_DROITS", $blog)) {
        $txt = CopixI18N::get('blog|blog.nav.droits');
        $type = 'acl';
        $current = ($action==8)? true : false;
        $url = CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>8));
        $menu[] = array('txt'=>$txt,'type' => $type, 'size'=> $size, 'current' => $current, 'url' => $url);
    }

    if (BlogAuth::canMakeInBlog("ADMIN_STATS", $blog)) {
        $txt = CopixI18N::get('blog|blog.nav.stats');
        $type = 'results';
        $current = ($action==9)? true : false;
        $url = CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>9));
        $menu[] = array('txt'=>$txt,'type' => $type, 'size'=> $size, 'current' => $current, 'url' => $url);
    }

    return $menu;
}

function killBadUrlChars ($url)
{
    if (!strlen($url))
        return $url;
    $result = strtolower($url);
    $result = killFrenchChars($result);
    $result	 = strtr($result,'&~#"\'\\/{}[]`@%&:| .?!','_____________________');
    $result = preg_replace("/[^a-zA-Z0-9]/i", "_", $result);
    $result = preg_replace("/_{2,}/i", "_", $result);

    // On ne peut pas commencer ni finir par autre chose qu'un chiffre ou une lettre
    while (!preg_match("/^([A-Za-z0-9])$/", substr($result,0,1), $regs)) {
        $result = substr($result,1);
    }
    while (!preg_match("/^([A-Za-z0-9])$/", substr($result,-1,1), $regs)) {
        $result = substr($result,0,strlen($result)-1);
    }

    return $result;
}

function timeToBD($time)
{
    $result = '';
    if(strlen($time)==5) $result = substr($time, 0, 2).substr($time, 3, 2);
    return $result;
}

function BDToTime($time)
{
    $result = '';
    if(strlen($time)==4) $result = substr($time, 0, 2).':'.substr($time, 2, 2);
    return $result;
}

function BDToDate($date)
{
    $result = '';
    if(strlen($date)==8) $result = substr($date, 6, 2).'/'.substr($date, 4, 2).'/'.substr($date, 0, 4);
    return $result;
}


function BDToDateTime($date, $time, $format)
{
    $result = '0000-00-00 00:00:00';
    if(strlen($date)==8 && strlen($time)==4) {
        switch ($format) {
            case "mysql" :
                $result = substr($date, 0, 4).'-'.substr($date, 4, 2).'-'.substr($date, 6, 2);
                $result .= ' ';
                $result .= substr($time, 0, 2).':'.substr($time, 2, 2).':00';
        }
    }
    return $result;
}


/**
 * Initialise un tableau avec toutes les fonctions d'un blog.
 */
function returnAllBlogFunctions()
{
    $results = array();
    $arMaj = array ('article_bfct', 'archive_bfct', 'find_bfct', 'link_bfct', 'rss_bfct', 'photo_bfct', 'option_bfct');
    foreach ($arMaj as $var){
        $function = new stdClass();
        $function->value  = $var;
        //$function->text = 'blog|dao.blogfunctions.fields.'.$var;
        $function->text = CopixI18N::get('blog|dao.blogfunctions.fields.'.$var);
        $function->selected = 0;
        array_push($results, $function);
    }
    //print_r($results);
    return $results;
}


/**
 * Retourne le blog d'un noeud (personne, �cole, classe...)
 *
 * @author Christophe Beyer <cbeyer@cap-tic.fr>
 * @since 2006/05/16
 * @param string $parent_type Type du parent (club, classe...)
 * @param string $parent_id Id du parent
 * @param array $pOptions Options [is_public] pour forcer un test sur le champ is_public
 * @return mixed NULL si pas de blog, le recordset sinon
 */
function getNodeBlog ($parent_type, $parent_id, $options=array())
{
    $blog = NULL;
    $trouve = false;
    $hisModules = Kernel::getModEnabled ($parent_type, $parent_id);
    foreach ($hisModules as $node) {
        //print_r($node);
        if ($trouve)
            break;
        if ($node->module_type == 'MOD_BLOG') {
            $dao = _dao("blog|blog");
            $blog = $dao->get($node->module_id);
            if (isset($options['is_public']) && $blog->is_public!=$options['is_public'])
                $blog = null;
            $trouve = true;
        }
    }
            //remove empty blog
            if(!empty($blog)){
                $result = _doQuery('SELECT COUNT(*) AS count FROM module_blog_article WHERE id_blog = :id AND is_online = 1', array(':id' => $blog->id_blog));
                if($result[0]->count == 0)
                    $blog = null;
            }

    return $blog;
}

/**
 * Cr�e un objet de type BLOG � partir d'un ID
 *
 * @author Christophe Beyer <cbeyer@cap-tic.fr>
 * @since 2007/06/01
 * @param integer $id_blog Id du blog
 * @return object Objet
 */
function create_blog_object ($id_blog)
{
    $blog = _record("blog|blog");
    $blog->id_blog = $id_blog;
    return $blog;
}

/**
 * Envoie un minimail de demande de publication aux propriétaires et à tout les modérateurs du blog
 * 
 * @author Aless <agiuliani@cap-tic.fr>
 * @param integer $id_blog Id du blog
 * @param integer $id_bact Id de l'article
 * @param integer $fromId Id du rédacteur
 * @param string $articleUrl Url de l'article
 */
function sendArticleNotif($id_blog, $id_bact, $fromId, $articleUrl)
{
    $articleDAO = CopixDAOFactory::create('blog|blogarticle');
    
    $userInfo = Kernel::getUserInfo();
    $auteur = $userInfo["prenom"] . " " . $userInfo["nom"] . " (" . $userInfo["login"] . ")";
    $title = CopixI18N::get('blog|blog.moderation.mailTitle');
    $categories = "";
    $nbCat = 0;
    foreach($articleDAO->findCategoriesForArticle($id_bact) as $cat) {
        $nbCat++;
        if($nbCat > 1) {
            $categories .= ", ";
        }
        $categories .= $cat->name_bacg;
    }          
    $message = CopixI18N::get('blog|blog.moderation.mailContent', array($auteur, _ioDAO('blog|blog')->getBlogById($id_blog)->name_blog, $categories, $articleUrl));
    
    $tabDest = getModeratorList($id_blog, PROFILE_CCV_MODERATE);
    
    $minimailService = & CopixClassesFactory::Create ('minimail|minimailService');
    return $minimailService->sendMinimail($title, $message, $fromId, $tabDest, CopixConfig::get('minimail|default_format'));
}

/**
 * Renvoie la liste des propriétaires et modérateurs du blog
 * 
 * @author Aless <agiuliani@cap-tic.fr>
 * @param integer $id_blog Id du blog
 * @param string $right le niveau de droit (PROFILE_CCV_MODERATE = 60)
 */
function getModeratorList($id_blog, $right)
{
    $sql = 'SELECT klb2u.user_id
            FROM kernel_link_bu2user klb2u
            JOIN kernel_link_user2node klu2n
                ON klu2n.user_id = klb2u.bu_id
                AND klu2n.user_type = klb2u.bu_type
            JOIN module_blog mb
                ON mb.id_blog = klu2n.node_id
            WHERE klu2n.droit >= :right
            AND klu2n.node_type = "MOD_BLOG"
            AND mb.id_blog = :blogId';
    
    $destList = _doQuery($sql, array(':right' => $right, ':blogId' => $id_blog));
    $tabDest = array();
    foreach($destList as $dest) {
        $tabDest[$dest->user_id] = $dest->user_id;
    }
    
    $bNode = Kernel::getContextParent('MOD_BLOG', $id_blog);
    $nType = $bNode['type'];
    $nId = $bNode['id'];
    
    $annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
    
    switch ($nType) {
        case "BU_CLASSE" :
            $owners = $annuaireService->getEnseignantInClasse($nId);
            foreach($owners as $owner) {
                $user_infos = Kernel::getUserInfo('USER_ENS', $owner['id']);
                $user_id = $user_infos['user_id'];
                $tabDest[$user_id] = $user_id;
            }
            break;
        case "BU_ECOLE" :
            $owners = $annuaireService->getDirecteurInEcole($nId);
            foreach($owners as $owner) {
                $user_infos = Kernel::getUserInfo('USER_ENS', $owner['id']);
                $user_id = $user_infos['user_id'];
                $tabDest[$user_id] = $user_id;
            }
            break;
        case "CLUB" :
            $kernelService = & CopixClassesFactory::Create ('kernel|kernel');
            $owners = $kernelService->getNodeChilds( "CLUB", $nId );
            foreach($owners as $owner) {
                if($owner['droit'] >= PROFILE_CCV_ADMIN)
                {
                    $user_infos = Kernel::getUserInfo($owner['type'], $owner['id']);
                    $user_id = $user_infos['user_id'];
                    $tabDest[$user_id] = $user_id;
                }
            }
            break;
        case "BU_VILLE" :
            $kernelService = & CopixClassesFactory::Create ('kernel|kernel');
            $owners = $kernelService->getNodeChilds( "BU_VILLE", $nId );
            foreach($owners as $owner) {
                if($owner['type']== 'USER_VIL')
                {
                    $user_infos = Kernel::getUserInfo($owner['type'], $owner['id']);
                    $user_id = $user_infos['user_id'];
                    $tabDest[$user_id] = $user_id;
                }
            }
            break;
        case "BU_GRVILLE" :
            $kernelService = & CopixClassesFactory::Create ('kernel|kernel');
            $owners = $kernelService->getNodeChilds( "BU_GRVILLE", $nId );
            foreach($owners as $owner) {
                if($owner['type']== 'USER_VIL')
                {
                    $user_infos = Kernel::getUserInfo($owner['type'], $owner['id']);
                    $user_id = $user_infos['user_id'];
                    $tabDest[$user_id] = $user_id;
                }
            }
            break;
    }
    
    return $tabDest;
}