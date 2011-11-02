<?php
/**
 * Regroupements - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Regroupements
 * @version     $Id$
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ActionGroupVilles extends CopixActionGroup {

	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');

	}



   function getGroup () {
   		if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN )
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );
			
		$tpl = new CopixTpl ();
		$tplGrVilles = new CopixTpl ();
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_regroupements.css"));

		$regroupements_service = & CopixClassesFactory::Create ('regroupements|regroupements');
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('regroupements|regroupements.villes.titre'));
		$tpl->assign ('MENU', $regroupements_service->getMenu() );
		
		$dao_grvilles_gr2ville = CopixDAOFactory::create("regroupements|grvilles_gr2ville");
		$dao_grvilles = CopixDAOFactory::create("regroupements|grvilles");
		$dao_villes = CopixDAOFactory::create("kernel|kernel_tree_vil");
		$villes = $dao_villes->findAll();
		$tplGrVilles->assign ('villes', $villes );
		
		if( _request("delete") ) {
			$dao_grvilles->delete(_request("delete"));
			$dao_grvilles_gr2ville->deleteByGroupe(_request("delete"));
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('regroupements|villes|'));
		}
		
		if( _request("save") && _request("save")==1 ) {
			$date = date("Y-m-d H:i:s");
			$user = Kernel::getUserInfo();
			
			if(_request("form_id")>0) {
				$grvilles_infos  = $dao_grvilles->get(_request("form_id"));
				$grvilles_infos->nom = _request("form_nom");
				$grvilles_infos->updated_at = $date;
				$grvilles_infos->updated_by = $user['login'];
				$dao_grvilles->update($grvilles_infos);
				$dao_grvilles_gr2ville->deleteByGroupe(_request("form_id"));
			} else {
				$grvilles_infos  = CopixDAOFactory::createRecord("regroupements|grvilles");
				$grvilles_infos->nom = _request("form_nom");
				if($grvilles_infos->nom=='') $grvilles_infos->nom='Sans nom';
				$grvilles_infos->updated_at = date("Y-m-d H:i:s");
				$grvilles_infos->updated_by = $user['login'];
				$dao_grvilles->insert($grvilles_infos);
			}
			
			$grvilles_gr2ville  = _record("regroupements|grvilles_gr2ville");
			$grvilles_gr2ville->id_groupe = $grvilles_infos->id;
			$grvilles_gr2ville->updated_at = $date;
			$grvilles_gr2ville->updated_by = $user['login'];
			foreach( $villes AS $ville ) {
				if( _request("ville_".$ville->vil_id_vi)==1 ) {
					$grvilles_gr2ville->id_ville = $ville->vil_id_vi;
					_dao("regroupements|grvilles_gr2ville")->insert($grvilles_gr2ville);
				}
			}
			
			if(_request("form_id")==0) {
				return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('regroupements|villes|', array('groupe'=>$grvilles_infos->id ) ));
			}
		}
		
		
		if( _request("groupe") ) {
			$tplGrVilles->assign ('grvilles_id', _request("groupe") );
			$tplGrVilles->assign ('grvilles_form', true );
			if(_request("groupe")>0) {
				// Edition d'un groupe
				$grvilles_infos  = $dao_grvilles->get(_request("groupe"));
				$grvilles_villes_raw = $dao_grvilles_gr2ville->findByGroupe(_request("groupe"));
				
				// Tableau index� par id de ville
				$grvilles_villes = array();
				foreach( $grvilles_villes_raw AS $grvilles_villes_item ) {
					$grvilles_villes[$grvilles_villes_item->id_ville] = $grvilles_villes_item;
				}
				$tplGrVilles->assign ('grvilles_infos', $grvilles_infos );
				$tplGrVilles->assign ('grvilles_villes', $grvilles_villes );
			} else {
				// Cr�ation d'un nouveau groupe
			}
		}
		
		$grvilles_list = $dao_grvilles->findAll();
		// print_r($grvilles_list);
		$tplGrVilles->assign ('grvilles_list', $grvilles_list );

		$main = $tplGrVilles->fetch ('getgrvilles.tpl');
		
		$tpl->assign ( 'MAIN', $main );
		
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}
	
}
?>
