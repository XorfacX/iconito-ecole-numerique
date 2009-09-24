<?php
/**
* @package	copix
* @version   $Id: adminblog.actiongroup.php,v 1.24 2009-03-03 16:46:57 cbeyer Exp $
* @author	Vallat C�dric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude('blog|blogauth');
_classInclude('blog|blogutils');
require_once (COPIX_UTILS_PATH.'CopixPager.class.php');

class ActionGroupAdminBlog extends CopixActionGroup {
	
	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');

	}

	/**
    * Pr�paration de l'�dition d'un blog.
    */
	function processGetShowBlog() {
		
		$id_blog = $this->getRequest('id_blog', null);
		
		$blogDAO = CopixDAOFactory::create('blog|blog');
		$blog = $blogDAO->get($id_blog);
		
		if (!BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog)){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.cannotManageBlog'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}
		
		if ($id_blog==null){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.param'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}
		$kind = $this->getRequest('kind', '0');
		$tpl = & new CopixTpl ();
		$tabBlogFunctions = null;
		$result = null;


		

		if($kind=='0') {
			// Articles
			
			CopixHTMLHeader::addCSSLink (_resource("styles/module_blog.css"));

			$selectCategory = $this->getRequest('selectCategory', '');
			$selectMonth = $this->getRequest('selectMonth', '');
			$id_bact = $this->getRequest('id_bact', '');
			$result = CopixZone::process ('ShowBlogArticle',
			array('id_blog'=>$id_blog,
			'blog'=>$blog,
			'kind'=>$kind,
			'selectCategory'=>$selectCategory,
			'selectMonth'=>$selectMonth,
			'id_bact'=>$id_bact,
			'p'=>$this->getRequest('p', '')
			));
		} else if($kind=='1') {
			// Cat�gories
			$result = CopixZone::process ('ShowBlogCategory',
			array('id_blog'=>$id_blog,
			'kind'=>$kind
			));
		} else if($kind=='2') {
			// Liens
			$result = CopixZone::process ('ShowBlogLink',
			array('id_blog'=>$id_blog,
			'kind'=>$kind
			));
		} else if($kind=='3') {
			// Photos
			$can = BlogAuth::canMakeInBlog('ADMIN_PHOTOS', $blog);
			if ($can) {
				$parent = Kernel::getModParentInfo("MOD_BLOG", $id_blog);
				if ($parent) {
					$mods = Kernel::getModEnabled ($parent['type'], $parent['id']);
					$mods = Kernel::filterModuleList ($mods, 'MOD_ALBUM');
					if ($mods && $mods[0]) {
						return new CopixActionReturn (COPIX_AR_REDIRECT,
							CopixUrl::get ('album||go', array('id'=>$mods[0]->module_id)));
					}
				}
			} // Si on arrive l�, c'est pas normal
			$result = CopixZone::process ('ShowBlogPhoto',
			array('id_blog'=>$id_blog,
			'kind'=>$kind
			));
		} else if($kind=='7') {
			// Documents
			$can = BlogAuth::canMakeInBlog('ADMIN_DOCUMENTS', $blog);
			if ($can) {
				$parent = Kernel::getModParentInfo("MOD_BLOG", $id_blog);
				if ($parent) {
					$mods = Kernel::getModEnabled ($parent['type'], $parent['id']);
					$mods = Kernel::filterModuleList ($mods, 'MOD_MALLE');
					if ($mods && $mods[0]) {
						return new CopixActionReturn (COPIX_AR_REDIRECT,
							CopixUrl::get ('malle||go', array('id'=>$mods[0]->module_id)));
					}
				}
			} // Si on arrive l�, c'est pas normal
			$result = CopixZone::process ('ShowBlogDocument',
			array('id_blog'=>$id_blog,
			'kind'=>$kind
			));
		} else if($kind=='5') {
			// Pages
			$result = CopixZone::process ('ShowBlogPage',
			array('id_blog'=>$id_blog,
			'kind'=>$kind
			));
		} else if($kind=='6') {
			// RSS
			$result = CopixZone::process ('ShowBlogFluxRss',
			array('id_blog'=>$id_blog,
			'kind'=>$kind
			));
		} else if($kind=='8') {
			// Droits particuliers sur le blog
			$result = CopixZone::process ('ShowBlogDroits',
			array('blog'=>$blog,
			'kind'=>$kind,
			'errors'=>$this->getRequest('errors'),
			'membres'=>$this->getRequest('membres'),
			'droit'=>$this->getRequest('droit')
			));
		} else if($kind=='9') {
			// Stats du blog
			$result = CopixZone::process ('stats|module',
			array('module_type'=>'MOD_BLOG',
			'module_id'=>$id_blog,
			'date'=>$this->getRequest('date'),
			'mois'=>$this->getRequest('mois'),
			'annee'=>$this->getRequest('annee'),
			'url'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$kind)),
			'errors'=>$this->getRequest('errors'),
			));
		} else {
			// Options
			$kind = 4;
			$tabFunctions = returnAllBlogFunctions();
			$blogFunctionsDAO = CopixDAOFactory::create('blog|blogfunctions');
			$resBlogFunctions = $blogFunctionsDAO->get($id_blog);
			$tabBlogFunctions = array();
			if($resBlogFunctions != null) {
				foreach($tabFunctions as $fct) {
					eval('if($resBlogFunctions->'.$fct->value.'==\'1\')array_push($tabBlogFunctions, $fct);');
				}
			}
		}
		
		$menu = array();
		
		$menu[] = array('txt'=>CopixI18N::get('blog|blog.nav.blog'), 'url'=>CopixUrl::get ('|', array("blog"=>$blog->url_blog)));

		if (BlogAuth::canMakeInBlog('ADMIN_ARTICLES', $blog))
			$menu[] = array('txt'=>CopixI18N::get('blog|blog.nav.articles'), 'url'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog,  "kind"=>0)));
			
		if (BlogAuth::canMakeInBlog('ADMIN_CATEGORIES', $blog))
			$menu[] = array('txt'=>CopixI18N::get('blog|blog.nav.categories'), 'url'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>1)));

		if (BlogAuth::canMakeInBlog('ADMIN_LIENS', $blog))
			$menu[] = array('txt'=>CopixI18N::get('blog|blog.nav.links'), 'url'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>2)));

		if (BlogAuth::canMakeInBlog('ADMIN_PHOTOS',$blog))
			$menu[] = array('txt'=>CopixI18N::get('blog|blog.nav.photos'), 'url'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>3)));
			
		if (BlogAuth::canMakeInBlog('ADMIN_DOCUMENTS',$blog))
			$menu[] = array('txt'=>CopixI18N::get('blog|blog.nav.documents'), 'url'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>7)));

		if (BlogAuth::canMakeInBlog('ADMIN_OPTIONS',$blog))
			$menu[] = array('txt'=>CopixI18N::get('blog|blog.nav.options'), 'url'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>4)));
			
		if (BlogAuth::canMakeInBlog('ADMIN_PAGES',$blog))
			$menu[] = array('txt'=>CopixI18N::get('blog|blog.nav.pages'), 'url'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>5)));
			
		if (BlogAuth::canMakeInBlog('ADMIN_RSS',$blog))
			$menu[] = array('txt'=>CopixI18N::get('blog|blog.nav.rss'), 'url'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>6)));
			
		if (BlogAuth::canMakeInBlog("ADMIN_DROITS", $blog))
			$menu[] = array('txt'=>CopixI18N::get('blog|blog.nav.droits'), 'url'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>8)));

		if (BlogAuth::canMakeInBlog("ADMIN_STATS", $blog))
			$menu[] = array('txt'=>CopixI18N::get('blog|blog.nav.stats'), 'url'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>9)));

		$returntoparent = Kernel::menuReturntoParent( "MOD_BLOG", $id_blog );
		if( $returntoparent ) $menu[] = $returntoparent;
		
		$tpl->assign ('TITLE_PAGE', $blog->name_blog);
		$tpl->assign ('MENU', $menu);
		$tpl->assign ('MAIN', CopixZone::process ('ShowBlog',
													array('id_blog'=>$id_blog,
															'blog'=>$blog,
															'kind'=>$kind,
															'tabBlogFunctions'=>$tabBlogFunctions,
															'RESULT'=>$result
													)));
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}



	/**
    * Pr�paration � l'affichage de la modif d'un blog
		* @todo rendre la cr�ation impossible
    */
 	function doPrepareEditBlog() {
		
		$id_blog = $this->getRequest('id_blog', null);
		
		$blogDAO = CopixDAOFactory::create('blog|blog');
		$blog = $blogDAO->get($id_blog);
			
		if (!BlogAuth::canMakeInBlog('ADMIN_OPTIONS',$blog)){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.cannotManageBlog'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}

		$tpl = & new CopixTpl ();

		$tabFunctions = returnAllBlogFunctions();

		
		if($id_blog!=null) {
			// EDITION D'UN BLOG
			
			//$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.blog.title'));
			$tpl->assign ('TITLE_PAGE', $blog->name_blog);

			$blogFunctionsDAO = CopixDAOFactory::create('blog|blogfunctions');
			$resBlogFunctions = $blogFunctionsDAO->get($id_blog);
		} else {
			// CREATION D'UN BLOG
			$blog = null;
			$resBlogFunctions = null;
			$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.create.blog.title'));
		}
		
		if (BlogAuth::canMakeInBlog('ADMIN_OPTIONS',$blog))
			$menu = '<a href="'.CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>4)).'">'.CopixI18N::get('blog|blog.nav.options').'</a>';
		$tpl->assign ('MENU', $menu);
		
		$tabBlogFunctions = array();
		foreach($tabFunctions as $fct) {
			if($resBlogFunctions != null) {
				eval('$fct->selected = $resBlogFunctions->'.$fct->value.';');
			} else {
				$fct->selected = 1;
			}
			array_push($tabBlogFunctions, $fct);
		}

		$tpl->assign ('MAIN', CopixZone::process ('EditBlog',
		array('id_blog'=>$id_blog,
		'blog'=>$blog,
		'kind'=>$this->getRequest('kind', 0),
		'tabBlogFunctions'=>$tabBlogFunctions
		)));
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}


	/**
    * 
    */
	function doValidBlog() {
		
		$id_blog = $this->getRequest('id_blog', null);
		
		$blogDAO = CopixDAOFactory::create('blog|blog');
		$blog = $blogDAO->get($id_blog);
		
		if (!BlogAuth::canMakeInBlog('ADMIN_OPTIONS',$blog)){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.cannotManageBlog'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}

		$tpl = & new CopixTpl ();

		// R�cup�ration de toutes les fonctions du blog
		$tabFunctions = returnAllBlogFunctions();
		$tabSelectedFunctions = (array) $this->getRequest('tabBlogFunctions', '');
		$tabBlogFunctions = array();
		foreach($tabFunctions as $fct) {
			if(in_array($fct->value, $tabSelectedFunctions)) {$fct->selected = 1;}
			array_push($tabBlogFunctions, $fct);
		}

		if($id_blog!=null) {
			// EDITION D'UN BLOG
			
			$blog->name_blog = $this->getRequest('name_blog', '');
			$blog->is_public = $this->getRequest('is_public', 1);
			$blog->has_comments_activated = $this->getRequest('has_comments_activated', 1);
			$blog->type_moderation_comments = $this->getRequest('type_moderation_comments', 'POST');
			$blog->default_format_articles = $this->getRequest('default_format_articles', 'wiki');
			//$blog->id_ctpt	 = $this->getRequest('id_ctpt', '');
			//$blog->url_blog	 = $this->getRequest('url_blog', '');
			// Gestion du LOGO
			if (is_uploaded_file($_FILES['logoFile']['tmp_name'])) {
				$file = COPIX_VAR_PATH.CopixConfig::get ('blog|logoPath').$blog->logo_blog;
				if (file_exists($file)) {
					@unlink($file);
				}
				$blog->logo_blog = $blog->id_blog.'_'.$_FILES['logoFile']['name'];
				$file = COPIX_VAR_PATH.CopixConfig::get ('blog|logoPath').$blog->logo_blog;
				move_uploaded_file ($_FILES['logoFile']['tmp_name'], $file);
			}
			$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.blog.title'));
			$errors = _dao('blog|blog')->check($blog);
			
			if($errors!=1) {
				// Traitement des erreurs
				$showErrors =  true;
			} else {
				// Modification dans la base
				$blogDAO->update($blog);
				/*
				$blogFunctionsDAO = CopixDAOFactory::create('blog|blogfunctions');
				$blogFunctionsDAO->updateBlogFunctions($id_blog, $tabBlogFunctions);
				*/
				return new CopixActionReturn (COPIX_AR_REDIRECT,
				CopixUrl::get ('blog|admin|showBlog', array('id_blog'=>$id_blog, 'kind'=>$this->getRequest('kind', 0))));
			}
		} else {
			// CREATION D'UN BLOG
			$blog = CopixDAOFactory::createRecord('blog');
			$blog->name_blog = $this->getRequest('name_blog', '');
			$blog->id_ctpt	 = $this->getRequest('id_ctpt', '');
			$blog->url_blog	 = $this->getRequest('url_blog', '');
			// Gestion du LOGO
			if (is_uploaded_file($_FILES['logoFile']['tmp_name'])) {
				$file = COPIX_VAR_PATH.CopixConfig::get ('blog|logoPath').$blog->logo_blog;
				if (file_exists($file)) {
					@unlink($file);
				}
				$blog->logo_blog = $blog->id_blog.'_'.$_FILES['logoFile']['name'];
				$file = COPIX_VAR_PATH.CopixConfig::get ('blog|logoPath').$blog->logo_blog;
				move_uploaded_file ($_FILES['logoFile']['tmp_name'], $file);
			}
			$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.create.blog.title'));
			$errors = _dao('blog|blog')->check($blog);
			if($errors!=1) {
				// Traitement des erreurs
				$showErrors =  true;
			} else {
				// Insertion dans la base
				$blogDAO->insert($blog);
				$blogFunctionsDAO = CopixDAOFactory::create('blog|blogfunctions');
				$blogFunctionsDAO->createBlogFunctions($blog->id_blog, $tabBlogFunctions);
				return new CopixActionReturn (COPIX_AR_REDIRECT,
				CopixUrl::get ('blog|admin|listBlog'));
			}
		}

		$tpl->assign ('MAIN', CopixZone::process ('EditBlog',
		array('id_blog'=>$id_blog,
		'blog'=>$blog,
		'errors'=>$errors,
		'showErrors'=>$showErrors,
		'kind'=>$this->getRequest('kind', 0),
		'tabBlogFunctions'=>$tabBlogFunctions
		)));
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	/**
    * Suppression d'un blog.
    */
	//fonction obsol�te � pr�sent, la fonction appell�e pour supprimer un blog se trouve dans kernelblog.class.php
	function doDeleteBlog (){
		_classInclude('blog|kernelblog');
		$id_blog = $this->getRequest('id_blog', null);
		$kernel = new KernelBlog;
		$kernel->delete($id_blog);
		/*if ($id_blog==null){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.param'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}

		$blogDAO = & CopixDAOFactory::create ('blog|blog');
		logo
		article
		page
		lien
		if (!$toDelete = $blogDAO->get ($id_blog)){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}

		if($this->getRequest('confirm', null) != null) {
			$blogDAO->delete($toDelete->id_blog);
			return new CopixActionReturn (COPIX_AR_REDIRECT,
			CopixUrl::get ('blog|admin|listBlog'));

		}

		return CopixActionGroup::process ('genericTools|messages::getConfirm',
		array ('confirm'=>CopixUrl::get ('blog|admin|deleteBlog',
		array('id_blog'=>$id_blog,
		'confirm'=>1)),
		'cancel'=>CopixUrl::get ('blog|admin|listBlog'),
		'message'=>CopixI18N::get ('blog.messages.confirmDeleteBlog'),
		'title'=>CopixI18N::get ('blog.get.delete.blog.title')));*/

	}

	/**
    * Propose url
    * @param 
    */
	function doSuggestBlogUrl() {
		/*
		$id_blog = $this->getRequest('id_blog', null);
		
		if (!BlogAuth::canMakeInBlog('ADMIN_OPTIONS',$blog)){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.cannotManageBlog'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}

		$tpl = & new CopixTpl ();

		
		if($id_blog!=null) {
			// EDITION D'UN BLOG
			$blogDAO = CopixDAOFactory::create('blog|blog');
			$blog = $blogDAO->get($id_blog);
			$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.blog.title'));
		} else {
			// CREATION D'UNE CATEGORIE
			$blog = CopixDAOFactory::createRecord('blog');
			$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.create.blog.title'));
		}
		$blog->name_blog = $this->getRequest('name_blog', '');
		$blog->id_ctpt	 = $this->getRequest('id_ctpt', '');
		$blog->url_blog = killBadUrlChars($blog->name_blog);

		// R�cup�ration de toutes les fonctions du blog
		$tabFunctions = returnAllBlogFunctions();
		$tabSelectedFunctions = (array) $this->getRequest('tabBlogFunctions', '');
		$tabBlogFunctions = array();
		foreach($tabFunctions as $fct) {
			if(in_array($fct->value, $tabSelectedFunctions)) {$fct->selected = 1;}
			array_push($tabBlogFunctions, $fct);
		}

		$tpl->assign ('MAIN', CopixZone::process ('EditBlog',
		array('id_blog'=>$id_blog,
		'blog'=>$blog,
		'kind'=>$this->getRequest('kind', 0),
		'tabBlogFunctions'=>$tabBlogFunctions
		)));
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		*/
	}

	/**
    * Suppression du logo.
    */
	function doDeleteLogoBlog() {
		
		$id_blog = $this->getRequest('id_blog', null);
		$blogDAO = & CopixDAOFactory::create ('blog|blog');
		$toUpdate = $blogDAO->get ($id_blog);
		
		if (!BlogAuth::canMakeInBlog('ADMIN_OPTIONS',$toUpdate)){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.cannotManageBlog'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}

		
		if ($id_blog==null){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.param'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}

		
		if (!$toUpdate){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}

		$file = COPIX_VAR_PATH.CopixConfig::get ('blog|logoPath').$toUpdate->logo_blog;
		if (file_exists($file)) {
			@unlink($file);
			$toUpdate->logo_blog = null;
			$blogDAO->update($toUpdate);
		}
		return new CopixActionReturn (COPIX_AR_REDIRECT,
		CopixUrl::get ('blog|admin|showBlog', array('id_blog'=>$id_blog, 'kind'=>$this->getRequest('kind', 0))));
	}



   /**
   * Formulaire de modification du style d'un blog
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/03/02
	 * @param integer $id_blog Id du blog
   */ 	
	 function doPrepareEditBlogStyle () {
		
		$id_blog = $this->getRequest('id_blog', null);
		$blogDAO = CopixDAOFactory::create('blog|blog');
		$blog = $blogDAO->get($id_blog);
			
		if (!BlogAuth::canMakeInBlog('ADMIN_OPTIONS',$blog)){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.cannotManageBlog'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}

		$tpl = & new CopixTpl ();


		if($id_blog!=null) {
			// EDITION D'UN BLOG
			
			//$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.blog.title'));
			$tpl->assign ('TITLE_PAGE', $blog->name_blog);
		}
		
		$menu = '<a href="'.CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>4)).'">'.CopixI18N::get('blog|blog.nav.options').'</a>';
		$tpl->assign ('MENU', $menu);
		
		$style_blog_file_src = CopixZone::process ('GetBlogCss', array('blog'=>$blog, 'editFile'=>true));

		$tpl->assign ('MAIN', CopixZone::process ('EditBlogStyle',
		array('id_blog'=>$id_blog,
		'blog'=>$blog,
		'style_blog_file_src'=>$style_blog_file_src,
		'kind'=>$this->getRequest('kind', 0),
		)));
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}


   /**
   * Soumission du formulaire de modification du style d'un blog
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/03/02
	 * @param integer $id_blog Id du blog
   */ 	
	function doValidBlogStyle () {
		
		$id_blog = $this->getRequest('id_blog', null);
		$blogDAO = CopixDAOFactory::create('blog|blog');
		$blog = $blogDAO->get($id_blog);
		
		if (!BlogAuth::canMakeInBlog('ADMIN_OPTIONS',$blog)){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.cannotManageBlog'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}

		$tpl = & new CopixTpl ();



		if($id_blog!=null) {
			// EDITION D'UN BLOG
			
			$style_blog_file = $this->getRequest('style_blog_file', 0);
			$style_blog_file_src = $this->getRequest('style_blog_file_src', 0);
			
			$blog->style_blog_file = $style_blog_file;
			
			//Gestion du fichier CSS personnalis�
			if ($style_blog_file==1) {
				$file = COPIX_VAR_PATH.CopixConfig::get ('blog|cssPath').$id_blog.'.css';
				
				$handle = fopen($file, 'w');
				if ($handle) {
					fwrite($handle, $style_blog_file_src);
					fclose($handle);
				}
			}

			$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.blog.title'));
			$errors = _dao('blog|blog')->check($blog);
			if($errors!=1) {
				// Traitement des erreurs
				$showErrors =  true;
			} else {
				// Modification dans la base
				$blogDAO->update($blog);
				return new CopixActionReturn (COPIX_AR_REDIRECT,
				CopixUrl::get ('blog|admin|showBlog', array('id_blog'=>$id_blog, 'kind'=>4)));
			}
		}
		
		$style_blog_file_src = 'aaa';
		
		$tpl->assign ('MAIN', CopixZone::process ('EditBlogStyle',
		array('id_blog'=>$id_blog,
		'blog'=>$blog,
		'style_blog_file_src'=>$style_blog_file_src,
		'errors'=>$errors,
		'showErrors'=>$showErrors,
		'kind'=>$this->getRequest('kind', 0),
		)));
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}


   /**
   * Inscription directe et effective de membres avec des droits sp�cifiques dans le blog, � partir de leurs logins
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/06/01
	 * @param integer $id Id du blog
	 * @param integer $kind Num�ro g�n�rique de la rubrique (ne pas y toucher)
	 * @param array $membres Les logins des membres � inscrire (s�par�s par des , ou ; si plusieurs)
	 * @param integer $droit Le droit � appliquer � ces membres
   */
	function doSubscribe () {

		$id = $this->getRequest('id', null);
		$kind = $this->getRequest('kind', null);
		$membres = $this->getRequest('membres', null);
		$droit = $this->getRequest('droit', null);

		$groupeService = & CopixClassesFactory::Create ('groupe|groupeService');
		$blogDAO = CopixDAOFactory::create('blog|blog');
		
		$blog = $blogDAO->get($id);
		$errors = array();
		
		if (!$blog || !$kind){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.param'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}
		
		if (!BlogAuth::canMakeInBlog('ADMIN_DROITS', $blog)){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.cannotManageBlog'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}
		
		if (!$droit)
			$errors[] = CopixI18N::get ('blog.error.subscribe.noRight');
		
		if (!$errors) {
			$tab_membres = $membres;
			$tab_membres = str_replace(array(" "), "", $tab_membres);
			$tab_membres = str_replace(array(",",";"), ",", $tab_membres);
			$tab_membres = explode (",", $tab_membres);
			
			$tabInscrits = array();
			// On v�rifie que les membres existent
			while (list(,$login) = each ($tab_membres)) {
				if (!$login) continue;
				if ($login == _currentUser()->getLogin()) {
					$errors[] = CopixI18N::get ('blog.error.subscribe.notHimself');
					continue;
				}
				
				$userInfo = Kernel::getUserInfo("LOGIN", $login);
				//print_r("login=$login");
				//print_r($userInfo);
				if (!$userInfo)
					$errors[] = CopixI18N::get ('blog.error.subscribe.memberNoUser', array($login));
				else {	// On regarde s'il est d�j� membre
					$droit2 = Kernel::getLevel( "MOD_BLOG", $id, $userInfo["type"], $userInfo["id"]);
					//Kernel::deb("login=$login / droit=$droit / droit2=$droit2");
					//print_r($userInfo);
					if ($droit2 > $droit)
						$errors[] = CopixI18N::get ('blog.error.subscribe.alreadyRight', array($login));
					else	// OK
						$tabInscrits[] = $userInfo;
				}
			}
		}

		//$errors[] = 'tmp';
		if ($errors) {
			return CopixActionGroup::process ('blog|AdminBlog::getShowBlog', array ('id_blog'=>$id, 'kind'=>$kind, 'membres'=>$membres, 'droit'=>$droit, 'errors'=>$errors));

		} else {
			// On ins�re les �ventuels membres
			
			while (list(,$user) = each ($tabInscrits)) {
				//print_r($user);
				Kernel::setLevel("MOD_BLOG", $id, $user["type"], $user["id"], $droit);
				CopixCache::clear ($user["type"].'-'.$user["id"], 'getnodeparents');
				CopixCache::clear ($user["type"].'-'.$user["id"], 'getmynodes');
			}

			$back = CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id, 'kind'=>$kind));
			return new CopixActionReturn (COPIX_AR_REDIRECT, $back);

		}
	}

   /**
   * Suppression des droits atribu�s � des membres sur un blog
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/06/04
	 * @param integer $id Id du blog
	 * @param integer $kind Num�ro g�n�rique de la rubrique (ne pas y toucher)
	 * @param array $membres Les membres � d�sinscrire (les valeurs sont de type USER_TYPE|USER_ID)
   */
	function doUnsubscribe () {

		$id = $this->getRequest('id', null);
		$kind = $this->getRequest('kind', null);
		$membres = $this->getRequest('membres', array());

		$groupeService = & CopixClassesFactory::Create ('groupe|groupeService');
		$blogDAO = CopixDAOFactory::create('blog|blog');
		
		$blog = $blogDAO->get($id);
		$errors = array();
		
		if (!$blog || !$kind){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.param'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}
		
		if (!BlogAuth::canMakeInBlog('ADMIN_DROITS', $blog)){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.cannotManageBlog'),
			'back'=>CopixUrl::get ('blog|admin|listBlog')));
		}
		
		if (!$errors) {
			
			foreach($membres as $membre) {
				list ($user_type,$user_id) = explode ("|", $membre);
				if ($user_type && $user_id) {
					//print ("user_type=$user_type / user_id=$user_id");
					Kernel::setLevel("MOD_BLOG", $id, $user_type, $user_id, 0);
					CopixCache::clear ($user_type.'-'.$user_id, 'getnodeparents');
					CopixCache::clear ($user_type.'-'.$user_id, 'getmynodes');
				}
			}

			$back = CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id, 'kind'=>$kind));
			return new CopixActionReturn (COPIX_AR_REDIRECT, $back);
		}

	}


}
?>
