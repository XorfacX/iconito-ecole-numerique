<?php


class ZoneFooter extends CopixZone {

	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		$toReturn = "";
		
		$arModulesPath = CopixConfig::instance ()->arModulesPath;
		
		$display = false;
		
		foreach ($arModulesPath as $modulePath) {
		
			$tpl_file = $modulePath.'kernel/templates/footer.tpl';

			if( !file_exists( $tpl_file ) ) continue;
			
			
			
			$display = true;
			
			// R�cup�ration des infos de g�olocalisation.
			$whereami = Kernel::whereAmI();
			
			if( isset( $whereami['BU_GRVILLE'] ) ) {
				$tpl->assign('grville_id', $whereami['BU_GRVILLE']['id'] );
				$tpl->assign('grville_nom', $whereami['BU_GRVILLE']['nom'] );
			}
			
			if( isset( $whereami['BU_VILLE'] ) ) {
				$tpl->assign('ville_id', $whereami['BU_VILLE']['id'] );
				$tpl->assign('ville_nom', $whereami['BU_VILLE']['nom'] );
			}
			
			if( isset( $whereami['BU_ECOLE'] ) ) {
				$tpl->assign('ecole_id', $whereami['BU_ECOLE']['id'] );
				$tpl->assign('ecole_nom', $whereami['BU_ECOLE']['nom'] );
			}
			
			if( isset( $whereami['BU_CLASSE'] ) ) {
				$tpl->assign('classe_id', $whereami['BU_CLASSE']['id'] );
				$tpl->assign('classe_nom', $whereami['BU_CLASSE']['nom'] );
			}
			
			// R�cup�ration des valeurs d'URL.
			if ($module = CopixRequest::get ('module'))
				$tpl->assign('module', $module);
			
			if ($action = CopixRequest::get ('action'))
				$tpl->assign('action', $action);
			
			// R�cup�ration des valeurs de sessions (personne).
			if( _currentUser()->getExtra('type') ) {
				$tpl->assign('user_type', _currentUser()->getExtra('type') );
				$tpl->assign('user_id', _currentUser()->getExtra('id') );
			}
			
		}
		
		// Si le fichier de template n'existe pas, pas d'erreur.
		if ($display)
			$toReturn = $tpl->fetch ('kernel|footer.tpl');
	
		return true;
	}



}
?>
