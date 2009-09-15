<?php

/**
 * Zone affichant une fiche d�taill�e d'un utilisateur
 * 
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneGetUserProfil extends CopixZone {

	/**
	 * Affiche la fiche d�taill�e d'un utilisateur (login, nom, pr�nom...)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/04
	 * @param string $type Type de personne (USER_ELE, USER_ELE...)
	 * @param integer $id Id
	 */
	function _createContent (&$toReturn) {
		
		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
		
		$type = isset($this->params["type"]) ? $this->params["type"] : NULL;
		$id = isset($this->params["id"]) ? $this->params["id"] : NULL;


		if ($type && $id) {	
			$usr = Kernel::getUserInfo ($type, $id);
			$usr['type_nom'] = Kernel::Code2Name ($usr['type']);
			
			// Avatar
			$avatar = Prefs::get('prefs', 'avatar', $usr['user_id']);
			$usr['avatar'] = ($avatar) ? CopixConfig::get ('prefs|avatar_path').$avatar : '';
			
			$parents = $enfants = array();
			if ($type == 'USER_ELE') { // Pour un �l�ve, on cherche ses parents
				$parents = $annuaireService->getParentsFromEleve ($id);
			} elseif ($type == 'USER_RES') { // Pour un parent, on cherche ses enfants
				$enfants = $annuaireService->getEnfantsFromParent ($id);
			}
			
			$tpl = & new CopixTpl ();
			$tpl->assign('usr', $usr);
			$tpl->assign('parents', $parents);
			$tpl->assign('enfants', $enfants);
	    $toReturn = $tpl->fetch ('getuserprofilzone.tpl');
			//$toReturn = $res;
		}
    return true;
	}

}

?>
