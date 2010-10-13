<?php

/*
 @file 	dashboard.actiongroup.php
 @desc		Dashboard constructor
 @version 	1.0.2
 @date 	2010-05-28 09:28:09 +0200 (Fri, 28 May 2010)
 @author 	S.HOLTZ <sholtz@cap-tic.fr>

 Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
 */


_classInclude('welcome|welcome');

class ActionGroupDefault extends enicActionGroup {

	function processDefault() {

		$tpl = & new CopixTpl ();
		$tplModule = & new CopixTpl ();

		//if user is not connected :
		if (1) {
			// S'il y a un blog prevu a l'accueil
			$dispBlog = false;
			$getKernelLimitsIdBlog = Kernel::getKernelLimits('id_blog');
			if ( $getKernelLimitsIdBlog ) {
					_classInclude ('blog|kernelblog');
					if ($blog = _ioDao('blog|blog')->getBlogById ($getKernelLimitsIdBlog)) {
						// On v�rifie qu'il y a au moins un article
						$stats = KernelBlog::getStats ($blog->id_blog);
						if ($stats['nbArticles']['value']>0)
							$dispBlog = true;
					}
				}
			if ($dispBlog) return CopixActionGroup::process ('blog|frontblog::getListArticle', array ('blog'=>$blog->url_blog));	
   	   
			CopixHtmlHeader::addOthers ('<link rel="alternate" href="'.CopixUrl::get ('public||rss', array()).'" type="application/rss+xml" title="'.htmlentities(CopixI18N::get ('public|public.rss.flux.title')).'" />');
      
      $tplModule->assign('user', _currentUser ());
      $result = $tplModule->fetch('welcome|welcome_'.CopixI18N::getLang().'.tpl');

			$tpl->assign ('TITLE_PAGE', ''.CopixI18N::get ('public|public.welcome.title'));  
			$tpl->assign('MAIN', $result);
			return new CopixActionReturn(COPIX_AR_DISPLAY, $tpl);
		}
	}
}