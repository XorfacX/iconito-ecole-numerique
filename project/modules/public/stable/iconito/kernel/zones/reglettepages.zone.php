<?php


class ZoneReglettePages extends CopixZone {

	

	/*
		Param�tres :
		nbPages = nombre total de page
		page = page courante
		url = URL acc�d�e en cliquant sur les pages (sans le param�tre page qui est ajout� automatiquement)
		
		*/
	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();

		//$service = & CopixClassesFactory::Create ('Album');
		$nbPages = intval($this->params['nbPages']);
		$page = intval($this->params['page']);
		$url = $this->params['url'];
		$autour = (isset($this->params['autour'])) ? intval($this->params['autour']) : 3;	// Nb de pages � afficher de chaque c�t� de la page courante
		$extremite = (isset($this->params['extremite'])) ? intval($this->params['extremite']) : 1;	// Nb de pages � chaque bout de la r�gle
		
		if ($nbPages<2) return true;
		
		$autourFrom = ($page-$autour<1) ? 1 : $page-$autour;
		$autourTo = ($page+$autour>$nbPages) ? $nbPages : $page+$autour;
		//print_r2($autourFrom);
		//print_r2($autourTo);
				
		$sep1 = $sep2 = '...';
		$pages1 = $pages2 = $pages3 = array();
		
		$pages1 = range(1,0+$extremite);
		$pages2 = range($autourFrom,$autourTo);
		$pages3 = range($nbPages-$extremite+1,$nbPages);
		if ($autourFrom<=$extremite+1) 	// Les premiers ... sautent
			$sep1 = '';
		if ($autourTo>=$nbPages-$extremite) 	// Les deuxi�mes ... sautent
			$sep2 = '';
		
		if (!$sep1) {	// On fusionne les premiers tableaux
			$pages2 = array_unique(array_merge ($pages1, $pages2));
			$pages1 = array();
		}
		if (!$sep2) {	// On fusionne les derniers tableaux
			$pages2 = array_unique(array_merge ($pages2, $pages3));
			$pages3 = array();
		}
				
		$pages = range(1,$nbPages);
		
		$tpl->assign('nbPages', $nbPages);
		$tpl->assign('page', $page);
		//$tpl->assign('pages', $pages);
		$tpl->assign('pages1', $pages1);
		$tpl->assign('pages2', $pages2);
		$tpl->assign('pages3', $pages3);
		$tpl->assign('url', $url);
		$tpl->assign('sep1', $sep1);
		$tpl->assign('sep2', $sep2);
		
		// retour de la fonction :
    $toReturn = $tpl->fetch ('reglettepages.tpl');
    return true;

	}



}
?>
