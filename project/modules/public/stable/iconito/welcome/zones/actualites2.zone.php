<?php

class ZoneActualites2 extends enicZone {

    /**
     * Affiche la liste des dernieres actualites des blogs publics
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/02/21
     * @param string $titre Titre a donner a la zone
     * @param integer $nb Nombre d'articles a afficher
     * @param boolean $chapo Si on veut afficher les chapos. Par defaut : false
     * @param boolean $hr Affiche un HR entre chaque article. Par defaut : false
     * @param boolean $showtitle Si on veut afficher le titre des articles. Par defaut : true
     * @param boolean $showdate Si on veut afficher la date des articles. Par defaut : true
     * @param boolean $dateformat Format de la date (si autre que i18n), envoy� dans strftime
     * @param boolean $showtime Si on veut afficher l'heure, sera format� par |time (HH:MM). Par d�faut : false
     * @param boolean $showcategorie Si on veut afficher les categories des articles. Par defaut : true
     * @param boolean $showparent Si on veut afficher l'origine de l'article. Par defaut : false
     * @param integer $blogId Pour limiter � un blog dont on connait l'ID
     * @param boolean $cache Si on veut mettre les donn�es en cache, selon les param�tres du cache welcome. Par d�faut : true
     * @param integer $cacheDuration Dur�e du cache, en secondes, pour �craser la valeur par d�faut du cache welcome
     */
    function _createContent(&$toReturn)
    {
        $this->addJs('js/iconito/module_welcome.js');

        $titre          = $this->getParam('titre');
        $nb             = (int)$this->getParam('nb');
        $chapo          = $this->getParam('chapo', false);
        $hr             = $this->getParam('hr', false);
        $showtitle      = $this->getParam('showtitle', true);
        $showdate       = $this->getParam('showdate', true);
        $dateformat     = $this->getParam('dateformat');
        $showtime       = $this->getParam('showtime', false);
        $showcategorie  = $this->getParam('showcategorie', true);
        $showparent     = $this->getParam('showparent', false);
        $blogId         = (int)$this->getParam('blogId');
        $cache          = $this->getParam('cache', true);
        $cacheDuration  = (int)$this->getParam('cacheDuration');
        
        if ($cache)
        {
            $cacheId = 'zoneActualites2';
            $cacheId .= '|'.$nb;
            $cacheId .= '|'.$chapo;
            $cacheId .= '|'.$hr;
            $cacheId .= '|'.$showtitle;
            $cacheId .= '|'.$showdate;
            $cacheId .= '|'.$dateformat;
            $cacheId .= '|'.$showtime;
            $cacheId .= '|'.$showcategorie;
            $cacheId .= '|'.$showparent;
            $cacheId .= '|'.$blogId;

            $existsParams = array();
            if ($cacheDuration > 0)
            {
                $existsParams['duration'] = $cacheDuration;
            }
            if (CopixCache::exists($cacheId, 'welcome', $existsParams))
            {
                $toReturn = CopixCache::read($cacheId, 'welcome', array(''));
                return true;
            }
            
        }
        
        
        $tpl = new CopixTpl ();
        $tpl->assign('titre',           $titre);
        $tpl->assign('chapo',           $chapo);
        $tpl->assign('hr',              $hr);
        $tpl->assign('showtitle',       $showtitle);
        $tpl->assign('showdate',        $showdate);
        $tpl->assign('dateformat',      $dateformat);
        $tpl->assign('showtime',        $showtime);
        $tpl->assign('showcategorie',   $showcategorie);
        $tpl->assign('showparent',      $showparent);
        $tpl->assign('blogId',          $blogId);
        $tpl->assign('cache',           $cache);

        $articles = _ioDAO('blog|blogarticle')->findPublic(array(
            'categories'    => true,
            'nb'            => $nb,
            'parent'        => $showparent,
            'blogId'        => $blogId,
        ));
        
        $tpl->assign('articles', $articles);
        $tpl->assign('listArticle', $articles);
        
        $toReturn = $tpl->fetch('zone_actualites2.tpl');

        if ($cache)
        {
            //_dump('CopixCache::write');
            CopixCache::write($cacheId, $toReturn, 'welcome');
        }
        
        return true;
    }

}

?>
