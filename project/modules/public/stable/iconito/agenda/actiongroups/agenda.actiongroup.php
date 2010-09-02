<?php
/**
* Actiongroup du module Agenda
* @package  Iconito
* @subpackage Agenda
* @author   Audrey Vassal
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude('agenda|agendaservices');
_classInclude('agenda|dateservices');
_classInclude('agenda|agendatype');
_classInclude('agenda|semaineparams');
require_once (COPIX_TEMP_PATH.'../utils/copix/smarty_plugins/modifier.wiki.php');

class ActionGroupAgenda extends CopixActionGroup {
	
	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');

	}
	
	/**
	* Fonction qui pr�pare l'affichage de la vue semaine
	*/
	function getVueSemaine (){
		CopixHTMLHeader::addCSSLink (_resource("styles/module_agenda.css"));
		CopixHtmlHeader::addJSLink(CopixUrl::get().'js/iconito/module_agenda.js');
		
		$obj = new AgendaService();
		$listAgendas = $obj->getAvailableAgenda();
		
		$agendaService  = new AgendaService;
		$dateService    = new DateService;
		
		if (($params = $this->_getSessionSemaineParams ()) == null){
			
			$params = new SemaineParams();
			
			$params->numSemaine = $this->getRequest('numSemaine', $dateService->dateToWeeknum(mktime()), true);
			$params->annee      = $this->getRequest('annee'     , date('Y'), true);
			
		}else{
			$params->numSemaine = $this->getRequest('numSemaine', $params->numSemaine, true);
			$params->annee      = $this->getRequest('annee'     , $params->annee, true);
		}
		
		//pour savoir si on a cliqu� sur un agenda � afficher
		if (_request('updateAgendaAffiches')){
			$arIdAgendas = array();			
			foreach($listAgendas as $agenda){
				if(_request('agendas_'.$agenda->id_agenda)){
					$arIdAgendas[$agenda->id_agenda] = $agenda->id_agenda;
				}
			}
			$agendaService->setAgendaAffiches($arIdAgendas);
		}


		//on r�cup�re en session les agendas � afficher
		$params->agendas = $agendaService->getAgendaAffiches();

		//on met � jour la session
		$this->_setSessionSemaineParams($params);
		
		//on determine la date de d�but et de fin de la semaine en cours d'affichage
		$dateDebutSemaine = date('Ymd', $dateService->numweekToDate($params->numSemaine, $params->annee, 1));//date au format bdd
		$dateFinSemaine   = date('Ymd', $dateService->numweekToDate($params->numSemaine, $params->annee, 0));//date au format bdd
		$arEventsSemaine  = array();

		//on r�cup�re tous les �v�nements de la semaine en cours de vue
		foreach((array)$params->agendas as $idAgenda){
			$arEventsSemaine[$idAgenda] = $agendaService->checkEventOfAgendaInBdd($idAgenda, $dateDebutSemaine, $dateFinSemaine);
		}
		//on classe ces �v�nements par jour
		$arEventByDay = $agendaService->getEventsByDay($arEventsSemaine, $dateDebutSemaine, $dateFinSemaine);
		
		//on ordonne les �v�nements par ordre croissant d'heure de d�but d'�v�nement dans la journ�e
		$arEventByDay = $agendaService->getEventsInOrderByDay($arEventByDay);
				
		//on d�termine l'heure de d�but et l'heure de fin pour l'affichage du calendrier
		//on travail sur des heures sans s�parateur pour pouvoir les comparer
		$heureDeb = CopixConfig::get('agenda|heuredebcal');
		$heureFin = CopixConfig::get('agenda|heurefincal');
		foreach ((array)$arEventByDay as $jours){	
			if (!isset($jours->events))
				continue;
			//print_r($jours);
			foreach ((array)$jours->events as $event){
				if($event->alldaylong_event == 0){
					if($dateService->heureWithSeparateurToheureWithoutSeparateur($event->heuredeb_event) < $dateService->heureWithSeparateurToheureWithoutSeparateur($heureDeb)){
						$heureDeb = $dateService->heureWithSeparateurToheureWithoutSeparateur($event->heuredeb_event);
					}
					if($dateService->heureWithSeparateurToheureWithoutSeparateur($heureFin) < $dateService->heureWithSeparateurToheureWithoutSeparateur($event->heurefin_event)){
						$heureFin = $dateService->heureWithSeparateurToheureWithoutSeparateur($event->heurefin_event);
					}
				}
			}			
		}
 
 		//on arrondit � l'heure inf�rieure pour l'heure de d�but et � l'heure sup�rieure pour l'heure de fin
		$heureDeb = substr($heureDeb, 0, 2);
		if(substr($heureFin, 2, 2) == 0){//si les minutes sont � 0, on arrondit � l'heure
			$heureFin = substr($heureFin, 0, 2);
		}
		else{//si les minutes ne sont pas � 0, on arrondit � l'heure sup�rieure
			$heureFin = substr($heureFin, 0, 2)+1;
		}

		//on r�cup�re les le�ons de la semaine � afficher
		$arLecons = $agendaService->getLeconsByDay((array)$params->agendas, $dateDebutSemaine, $dateFinSemaine);
		
		//r�cup�ration de la liste des agendas affich�s
    	$listAgendasAffiches = $obj->getAgendaAffiches();

		//template pour agenda
		$tplAgenda = & new CopixTpl();
		$tplAgenda->assign ('MAIN_AGENDA', CopixZone::process('agenda|agendavuesemaine', array('elementsSemaineAffichee'=>$params,
																								'arEventByDay'=>$arEventByDay,
																								'heureDeb'=>$heureDeb,
																								'heureFin'=>$heureFin,
																								//'arCouleurAgenda'=>$arCouleurAgenda,
																								'arLecons'=>$arLecons)));
		
    	$title = $obj->getCurrentTitle ();

		//template principal
		$tpl = & new CopixTpl();
		$tpl->assign ('TITLE_PAGE', $title['title']);
		
		// CONSTRUCTION DU MENU
		// S.Holtz 2010.09
		$menu = array();
		
		// Affichage hebdomadaire
		$menu_txt = CopixI18N::get('agenda.menu.back');
		$menu_type = 'week';
		$menu_url = CopixUrl::get ('agenda|agenda|vueSemaine');
		$menu[] = array('txt'=>$menu_txt,'type' => $menu_type, 'current' => true, 'url' => $menu_url);
		
		// Liste des agendas (popup)
		$menu_txt = CopixI18N::get ('agenda|agenda.menu.agendalist');
		$menu_type = 'agendalist';
		$menu_behavior = 'popup500x300';
		$menu_url = CopixUrl::get ('agenda|agenda|agendaList');
		$menu[] = array('txt'=>$menu_txt,'type' => $menu_type, 'current' => false, 'behavior' => $menu_behavior, 'url' => $menu_url);
		
		// Nouvel evenement
		$menu_txt = CopixI18N::get('agenda.menu.ajoutEvent');
		$menu_type = 'create';
		$menu_url = CopixUrl::get ('agenda|event|create');
		$menu[] = array('txt'=>$menu_txt,'type' => $menu_type, 'current' => false, 'url' => $menu_url);
		
		// Export
		$menu_txt = CopixI18N::get('agenda.menu.export');
		$menu_type = 'export';
		$menu_url = CopixUrl::get ('agenda|importexport|prepareExport');
		$menu[] = array('txt'=>$menu_txt,'type' => $menu_type, 'current' => false, 'url' => $menu_url);

		$tpl->assign ('MENU', $menu);
		// FIN CONSTRUCTION DU MENU
		
//		$tpl->assign ('MENU', CopixZone::process('agenda|agendamenu', array('listAgendas'=>$listAgendas, 'listAgendasAffiches'=>$listAgendasAffiches, 'parent'=>(isset($title['parent'])?$title['parent']:''))));
		
		$tpl->assign ('MAIN', $tplAgenda->fetch('agenda|main.agenda.tpl'));
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);	
	}
	
	/**
	* Fonction qui pr�pare l'affichage de la zone "aujourd'hui"
	*/
	function getZoneToday (){
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_agenda.css"));
		
		$agendaService  = new AgendaService;
		
		//pour savoir si on a cliqu� sur un agenda � afficher
		if (_request('updateAgendaAffiches')){
			$arIdAgendas = array();			
			foreach($listAgendas as $agenda){
				if(_request('agendas_'.$agenda->id_agenda)){
					$arIdAgendas[$agenda->id_agenda] = $agenda->id_agenda;
				}
			}
			$agendaService->setAgendaAffiches($arIdAgendas);
		}

		//on r�cup�re en session les agendas � afficher
		$arAgendasAffiches = $agendaService->getAgendaAffiches();

		$tplZoneAujourdhui = & new CopixTpl();
		$tplZoneAujourdhui->assign ('MAIN_AGENDA', CopixZone::process('agenda|agendatoday', array('day'=>_request('day'),
																								  'arAgendasAffiches'=>$arAgendasAffiches)));
																								  
		//template principal
		$tpl = & new CopixTpl();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('agenda|agenda.titlePage.titre'));
		$tpl->assign ('MAIN', $tplZoneAujourdhui->fetch('agenda|main.agenda.tpl'));
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);		
	}
	
	/**
	* Mise en session des param�tres de la semaine � afficher
	* @access : private.
	*/
	function _setSessionSemaineParams ($toSet){
		$toSession = ($toSet !== null) ? serialize($toSet) : null;
		_sessionSet('modules|agenda|vue_semaine', $toSession);
	}
	
	
	/**
	* R�cup�ration en session des param�tres de la semaine � afficher
	* @access : private.
	*/
	function _getSessionSemaineParams () {
		$inSession = _sessionGet ('modules|agenda|vue_semaine');
		return ($inSession) ? unserialize ($inSession) : null;
	}

	/**
	* Entr�e g�n�rique dans un agenda
	* @author Christophe Beyer <cbeyer@cap-tic.fr> 
	* @since 2006/08/24
	* @param integer $id Id de l'agenda (si aucun, l'envoie dans l'agenda perso)
	*/
	function go () {
		$id = $this->getRequest('id', null);
		$dao = CopixDAOFactory::create('agenda|agenda');
		if ($id==null) {	// Si pas d'id, on l'envoie dans son agenda perso
			$userInfo = Kernel::getUserInfo();
			// Cr�ation des modules inexistants.
			Kernel::createMissingModules( $userInfo["type"], $userInfo["id"] );
			// Liste des modules activ�s.
			$modsList = Kernel::getModEnabled( $userInfo["type"], $userInfo["id"] );
			foreach ($modsList AS $modInfo) {
				if( $modInfo->module_type == "MOD_AGENDA" && $modInfo->module_id) {
					$id = $modInfo->module_id;
				}
			}
		}
		if ($id && $agenda=$dao->get($id)) {
      $this->_setSessionSemaineParams (null);
			$obj = new AgendaService();
			$obj->setAgendaAffiches(array($id=>$id));
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('agenda|agenda|'));
		}
		return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||') );
	}

	/**
	* Liste des agendas disponibles
	* @author Christophe Beyer <cbeyer@cap-tic.fr> 
	* @since 2006/08/24
	* @param integer $id Id de l'agenda (si aucun, l'envoie dans l'agenda perso)
	*/
	function processAgendaList () {
		$serviceAuth   = new AgendaAuth;
		$serviceType   = new AgendaType;
		$serviceAgenda = new AgendaService;
    
		$tpl = & new CopixTpl ();
		
		$agendaDispos = AgendaService::getAvailableAgenda();
		$agendaAffiches = AgendaService::getAgendaAffiches();

	    $ableToWrite = $ableToModerate = false;
	    
		//on v�rifie les droits des utilisateurs sur la liste des agendas affich�s
		foreach((array)$agendaAffiches as $id_agenda){
			//on v�rifie si l'utilisateur a les droits d'�criture sur un des agendas affich�
      		//print_r($serviceAuth->getWriteAgenda());
			if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getWriteAgenda()){
				$ableToWrite = true;
				break;
			}
		}
		
		//on v�rifie les droits des utilisateurs sur la liste des agendas affich�s
		foreach((array)$agendaAffiches as $id_agenda){
			//on v�rifie si l'utilisateur a les droits d'import
			if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getModerate()){
				$ableToModerate = true;
				break;
			}
		}		

		$listeFiltre = $agendaDispos;
		//on v�rifie les droits de lecture des utilisateurs
		foreach((array)$listeFiltre as $key=>$agenda){
			//on v�rifie si l'utilisateur a les droits de lecture sur la liste des agendas
			if($serviceAuth->getCapability($agenda->id_agenda) < $serviceAuth->getRead()){
				unset($listeFiltre[$key]);
			}
		}
				
		//on construit le tableau de couleurs associ�es au type d'agenda
		$arColorByIdAgenda = array();
		foreach((array)$listeFiltre as $agenda){
			$arColor = $serviceType->getColors($serviceAgenda->getTypeAgendaByIdAgenda($agenda->id_agenda));
			$i = 0;
			foreach($arColorByIdAgenda as $idAgenda=>$couleurAgenda){	
				if($arColorByIdAgenda[$idAgenda] == $arColor[$i]){
					$i = $i + 1;
				}
			}
			if($i < count($arColor)){
				$arColorByIdAgenda[$agenda->id_agenda] = $arColor[$i];
			}
			else{
				$arColorByIdAgenda[$agenda->id_agenda] = $arColor[0];
			}
		}		

		$ppo = new CopixPPO ();
		$ppo->arColorByIdAgenda = $arColorByIdAgenda;
		$ppo->listAgendas = $listeFiltre;
		$ppo->agendasSelectionnes = $agendaAffiches;
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_agenda.css"));
		return _arPPO ($ppo, array ('template'=>'popup_agendalist.agenda.tpl', 'mainTemplate'=>'default|main_popup.php'));
	}
}
?>
