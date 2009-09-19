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
_classInclude('agenda|importservices');
_classInclude('agenda|exportservices');
_classInclude('agenda|agendaauth');

class ActionGroupImportExport extends CopixActionGroup {

	
	/**
	* Fonction appelée lorsque l'on clique sur le lien import du menu
	* Récupère en session les agendas en cours de visualisation
	* récupère l'objet importParams en session s'il existe et créer sinon puis stock en session
	* @author Audrey Vassal <avassal@sqli.com> 
	*/
	function processGetPrepareImport(){	
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_agenda.css"));
		
		$serviceAuth   = new AgendaAuth;
		$serviceAgenda = new AgendaService;
		
		//récupération de la liste des agendas affichés
		$listAgendasAffiches = $serviceAgenda->getAgendaAffiches();

		//on vérifie les droits des utilisateurs sur la liste des agendas affichés
		foreach((array)$listAgendasAffiches as $id_agenda){
			//on vérifie si l'utilisateur a les droits d'import sur un des agendas affiché
			if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getModerate()){
				$ableToModerate = true;
			}
		}		
		if(!$ableToModerate){
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array ('message'=>CopixI18N::get ('agenda.error.enableToWrite'),
						'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
		}		
		
		//on récupère en session la liste des agendas en cours de visualisation
		$arTitleAgendasAffiches = $serviceAgenda->getArTitleAgendaByArIdAgenda($listAgendasAffiches);
		
		if (!$importParams = $this->_getSessionImport ()){
			//initialisation de l'objet importParams avec le premier agenda affiché de la liste			
			$importParams->id_agenda = isset($arAgendasAffiches) && is_array($arAgendasAffiches) ? current($arAgendasAffiches) : null;
			$this->_setSessionImport($importParams);
		}

		//récupération de la liste des agendas en bdd (pour l'affichage du menu)
		$listAgendas = $serviceAgenda->getAvailableAgenda();
				
		//template pour agenda
		$tplAgenda = & new CopixTpl();
		//$tplAgenda->assign ('MENU_AGENDA', CopixZone::process('agenda|agendamenu', array('listAgendas'=>$listAgendas, 'listAgendasAffiches'=>$listAgendasAffiches)));
		$tplAgenda->assign ('MAIN_AGENDA', CopixZone::process('agenda|agendaimport', array('arTitleAgendasAffiches'=>$arTitleAgendasAffiches, 'e'=>$this->getRequest('e'), 'errors'=>$this->getRequest('errors'), 'importParams'=>$importParams)));
		
		//template principal
		$tpl = & new CopixTpl();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('agenda|agenda.message.import'));
    $tpl->assign ('MENU', CopixZone::process('agenda|agendamenu', array('listAgendas'=>$listAgendas, 'listAgendasAffiches'=>$listAgendasAffiches)));
		$tpl->assign ('MAIN'      , $tplAgenda->fetch('agenda|main.agenda.tpl'));
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);	
	}
	
	
	/**
	* Fonction appelée lorsque l'on clique sur le bouton 'import'
	* Appel la méthode privée _validFromFormImportParams
	* vérifie les infos saisies dans le formulaire
	* stock l'objet en session
	* @author Audrey Vassal <avassal@sqli.com> 
	*/
	function doImport(){
		$serviceAuth   = new AgendaAuth;
		$serviceImport = new ImportService;

		//demande de mettre l'objet à jour en fonction des valeurs saisies dans le formulaire
		if (!$importParams = $this->_getSessionImport()){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('agenda.error.cannotFindSession'),
			'back'=>CopixUrl::get ('agenda|agenda|vueSemaine')));
		}
		
		$this->_validFromFormImportParams ($importParams);
		
		//on vérifie les droits
		if($serviceAuth->getCapability($importParams->id_agenda) < $serviceAuth->getModerate()){
				return CopixActionGroup::process ('genericTools|Messages::getError',
				array ('message'=>CopixI18N::get ('agenda.error.enableToWrite'),
						'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
		}
		
		
		$errors = $this->_checkImport();

		if (count($errors)>0){			
			$this->_setSessionImport($importParams);
			return CopixActionGroup::process('agenda|ImportExport::getPrepareImport', array('e'=>1, 'errors'=>$errors));
		}
		else{
			if(is_uploaded_file ($_FILES['import_ordi']['tmp_name'])){
					move_uploaded_file ($_FILES['import_ordi']['tmp_name'], CopixConfig::get ('agenda|tempfiles') . 'import.ics');
					$file[] = CopixConfig::get ('agenda|tempfiles') . 'import.ics';					
			}
			else{
				if(_request('import_internet') != null){
					
					$filename = _request('import_internet');
					$handle = fopen($filename, "rb");
					$contents = '';
					while (!feof($handle)) {
					  $contents .= fread($handle, 8192);
					}
					$handleToWrite = fopen(CopixConfig::get ('agenda|tempfiles') . 'import.ics', 'w+');
					fwrite($handleToWrite, $contents);
					$file[] = CopixConfig::get ('agenda|tempfiles') . 'import.ics';
				}
				else{
					return CopixActionGroup::process ('genericTools|Messages::getError',
					array ('message'=>CopixI18N::get ('agenda.error.cannotDownloadFile'),
							'back'=>CopixUrl::get ('agenda|importexport|prepareImport')));
				}
				
			}

			$icalParser   = CopixClassesFactory::create('ical_parser');
			$importEvents = $icalParser->parse($file);
			if($icalParser->parse($file) == false){
				return CopixActionGroup::process ('genericTools|Messages::getError',
						array ('message'=>CopixI18N::get ('agenda.error.cannotFindFile'),
								'back'=>CopixUrl::get ('agenda|importexport|prepareImport')));
			}
			if($importParams->option == 1){//cas où on réalise l'import sans vider
				$nbInsertions = $serviceImport->importSansVider($importEvents, $importParams->id_agenda);
			}
			else{
				$serviceImport->viderBase($importEvents, $importParams->id_agenda);
				$nbInsertions = $serviceImport->importSansVider($importEvents, $importParams->id_agenda);
			}			
		}
		//on efface le fichier temporaire créé pour faire l'import
		unlink(CopixConfig::get ('agenda|tempfiles') . 'import.ics');
		
		//on vide la session
		$this->_setSessionImport(null);
		return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('agenda|importexport|afterImport', array('nbInsertions'=>$nbInsertions)));
	}
	
	
	/**
	* Fonction appelée à partir de doImport
	* Appel la zone agendamenu et agendaafterimport
	* @since 2006/08/16
	* @author Audrey Vassal <avassal@sqli.com> 
	*/
	function getAfterImport(){
	
		CopixHTMLHeader::addCSSLink (_resource("styles/module_agenda.css"));
	
		//récupération de la liste des agendas en bdd (pour l'affichage du menu)
		$serviceAgenda = new AgendaService;
		$listAgendas   = $serviceAgenda->getAvailableAgenda();
		
		//récupération de la liste des agendas affichés
		$listAgendasAffiches = $serviceAgenda->getAgendaAffiches();		
		
		//template pour agenda
		$tplAgenda = & new CopixTpl();
		$tplAgenda->assign ('MAIN_AGENDA', CopixZone::process('agenda|agendaafterimport', array('nbInsertions'=>_request('nbInsertions'))));
		
		//template principal
		$tpl = & new CopixTpl();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('agenda|agenda.message.import'));
    $tpl->assign ('MENU', CopixZone::process('agenda|agendamenu', array('listAgendas'=>$listAgendas, 'listAgendasAffiches'=>$listAgendasAffiches)));
		$tpl->assign ('MAIN'      , $tplAgenda->fetch('agenda|main.agenda.tpl'));
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}
	
	
	/**
	* Fonction appelée lorsque l'on clique sur le lien export du menu
	* Récupère en session les agendas visualisables de l'utilisateur
	* récupère l'objet exportParams en session s'il existe, le créer sinon, puis stock en session
	* @since 2006/08/17
	* @author Audrey Vassal <avassal@sqli.com>
	* appelle les zones agendamenu et agendaexport
	*/
	function processGetPrepareExport(){	

		CopixHTMLHeader::addCSSLink (_resource("styles/module_agenda.css"));
		
		$serviceAuth   = new AgendaAuth;		
		$serviceAgenda = new AgendaService;
		
		//on vérifie les droits des utilisateurs sur la liste des agendas affichés
		$listAgendasAffiches = $serviceAgenda->getAgendaAffiches();
		foreach((array)$listAgendasAffiches as $id_agenda){
			//on vérifie si l'utilisateur a les droits d'écriture sur un des agendas affiché
			if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getRead()){
				$ableToRead = true;
			}
		}		
		if(!$ableToRead){
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array ('message'=>CopixI18N::get ('agenda.error.enableToWrite'),
						'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
		}
		
		//on récupère en session la liste des agendas en cours de visualisation
		$arAgendasAffiches = $serviceAgenda->getAgendaAffiches();
		$arTitleAgendasAffiches = $serviceAgenda->getArTitleAgendaByArIdAgenda($arAgendasAffiches);
		
		if (!$exportParams = $this->_getSessionExport ()){
			//initialisation de l'objet exportParams avec le premier agenda affiché de la liste
			$exportParams->id_agenda = current($arAgendasAffiches);
			$this->_setSessionExport($exportParams);
		}

		//récupération de la liste des agendas en bdd (pour l'affichage du menu)
		$listAgendas = $serviceAgenda->getAvailableAgenda();
		//récupération de la liste des agendas affichés
		$listAgendasAffiches = $serviceAgenda->getAgendaAffiches();

		//template pour agenda
		$tplAgenda = & new CopixTpl();
		//$tplAgenda->assign ('MENU_AGENDA', CopixZone::process('agenda|agendamenu'  , array('listAgendas'=>$listAgendas, 'listAgendasAffiches'=>$listAgendasAffiches)));
		$tplAgenda->assign ('MAIN_AGENDA', CopixZone::process('agenda|agendaexport', array('arTitleAgendasAffiches'=>$arTitleAgendasAffiches, 'e'=>$this->getRequest('e'), 'errors'=>$this->getRequest('errors'), 'exportParams'=>$exportParams)));
	
		//template principal
		$tpl = & new CopixTpl();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('agenda|agenda.message.export'));
    $tpl->assign ('MENU', CopixZone::process('agenda|agendamenu', array('listAgendas'=>$listAgendas, 'listAgendasAffiches'=>$listAgendasAffiches)));
		$tpl->assign ('MAIN'      , $tplAgenda->fetch('agenda|main.agenda.tpl'));
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);	
	}
	
	
	/**
	* Fonction appelée lorsque l'on clique sur le bouton 'import'
	* Appel la méthode privée _validFromFormImportParams
	* vérifie les infos saisies dans le formulaire
	* stock l'objet en session
	* @author Audrey Vassal <avassal@sqli.com> 
	*/
	function doExport(){
		$serviceAuth   = new AgendaAuth;
		$serviceExport = new ExportService;
		$agendaService = new AgendaService;
		$dateService   = new DateService;

		//demande de mettre l'objet à jour en fonction des valeurs saisies dans le formulaire
		if (!$exportParams = $this->_getSessionExport()){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('agenda.error.cannotFindSession'),
			'back'=>CopixUrl::get ('agenda|agenda|vueSemaine')));
		}		
		
		//on vérifie les droits des utilisateurs sur la liste des agendas sélectionnés
		foreach((array)$this->getRequest('agenda') as $id_agenda){
			//on vérifie si l'utilisateur a les droits d'écriture sur un des agendas affiché
			if($serviceAuth->getCapability($id_agenda) < $serviceAuth->getRead()){
				return CopixActionGroup::process ('genericTools|Messages::getError',
				array ('message'=>CopixI18N::get ('agenda.error.enableToWrite'),
						'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
			}
		}		
				
		$this->_validFromFormExportParams ($exportParams);

		$errors = $this->_checkExport($exportParams);

		if (count($errors)>0){			
			$this->_setSessionExport($exportParams);
			return CopixActionGroup::process('agenda|ImportExport::getPrepareExport', array('e'=>1, 'errors'=>$errors));
		}
		else{
			
			//var_dump($exportParams);
			//die();
		
			//on récupère tous les évènements des agendas cochés dans la période demandée
			foreach((array)_request('agenda') as $idAgenda){
				$arEventsPeriode[$idAgenda] = $agendaService->checkEventOfAgendaInBdd($idAgenda, CopixI18N::dateToBD($exportParams->datedeb_export), CopixI18N::dateToBD($exportParams->datefin_export));
			}
			
			//on classe ces évènements par jour
			$arEventByDay = $agendaService->getEventsByDay($arEventsPeriode, CopixI18N::dateToBD($exportParams->datedeb_export), CopixI18N::dateToBD($exportParams->datefin_export));
			
			//on ordonne les évènements par ordre croissant d'heure de début d'évènement dans la journée
			$arEventByDay = $agendaService->getEventsInOrderByDay($arEventByDay);
			$content = $serviceExport->getFileICal($arEventByDay, CopixI18N::dateToBD($exportParams->datedeb_export), CopixI18N::dateToBD($exportParams->datefin_export));
		}
		
		//on vide la session
		
		//$this->_setSessionExport(null);	
		return new CopixActionReturn (COPIX_AR_DOWNLOAD_CONTENT, $content, 'agenda.ics');
	}
	
	
	/**
	* Fonction qui fait la vérification sur les champs de saisie du formulaire d'import
	* @author Audrey Vassal <avassal@sqli.com>
	* @access: private
	* @return array $toReturn tableau qui contient les erreurs de saisie de l'utilisateur
	*/
	function _checkImport (){
		$toReturn = array();
				
		//vérification si les champs sont bien remplis
		if (!is_uploaded_file ($_FILES['import_ordi']['tmp_name']) && (_request('import_internet') == null || _request('import_internet') == 'http://')){
			$toReturn[] = CopixI18N::get('agenda|agenda.error.nofile');
		}
		
		if (_request('option') == null){
			$toReturn[] = CopixI18N::get('agenda|agenda.error.nooption');
		}
		
		return $toReturn;
	}
	
	/**
	* Mise en session des paramètres de l'évènement en édition
	* @access: private.
	*/
	function _setSessionImport ($toSet){
		$toSession = ($toSet !== null) ? serialize($toSet) : null;
		_sessionSet('modules|agenda|import_agenda', $toSession);
	}
	
	
	/**
	* Récupération en session des paramètres de l'évènement en édition
	* @access: private.
	*/
	function _getSessionImport () {
		$inSession = _sessionGet ('modules|agenda|import_agenda');
		return ($inSession) ? unserialize ($inSession) : null;
	}
	
	
	/**
	* @access: private.
	*/
	function _validFromFormImportParams (& $toUpdate){
		$toCheck = array ('id_agenda', 'import_ordi', 'import_internet','option');
		foreach ($toCheck as $elem){
			if (_request($elem)){
				$toUpdate->$elem = _request($elem);
			}
		}
	}
	
	
	/**
	* Fonction qui fait la vérification sur les champs de saisie du formulaire d'import
	* @author Audrey Vassal <avassal@sqli.com>
	* @access: private
	* @return array $toReturn tableau qui contient les erreurs de saisie de l'utilisateur
	*/
	function _checkExport ($obj) {
		$toReturn = array();
		
		$datedeb 		 = $obj->datedeb_export;
		$datefin 		 = $obj->datefin_export;
		
		$datedebTs 		 = CopixDateTime::dateToTimestamp($datedeb);
		$datefinTs 		 = CopixDateTime::dateToTimestamp($datefin);
		
		//vérification si les champs sont bien remplis
		if (!$datedeb) {
			$toReturn[] = CopixI18N::get('agenda|agenda.error.nodatedeb');
		}
		
		if (!$datefin) {
			$toReturn[] = CopixI18N::get('agenda|agenda.error.nodatefin');
		}
			
		//vérification sur la cohérence des dates de début et de fin
		if ($datedeb && $datefin && $datedebTs && $datefinTs && $datedebTs > $datefinTs){
			$toReturn[] = CopixI18N::get('agenda|agenda.error.inversiondate');
		}
		
		if (!$obj->agenda) {
			$toReturn[] = CopixI18N::get('agenda|agenda.error.noagenda');
		}
			
		return $toReturn;
	}
	
	
	/**
	* Mise en session des paramètres de l'évènement en édition
	* @access: private.
	*/
	function _setSessionExport ($toSet){
		$toSession = ($toSet !== null) ? serialize($toSet) : null;
		_sessionSet('modules|agenda|export_agenda', $toSession);
	}
	
	
	/**
	* Récupération en session des paramètres de l'évènement en édition
	* @access: private.
	*/
	function _getSessionExport () {
		$inSession = _sessionGet ('modules|agenda|export_agenda');
		return ($inSession) ? unserialize ($inSession) : null;
	}
	
	
	/**
	* @access: private.
	*/
	function _validFromFormExportParams (& $toUpdate){
		$toCheck = array ('id_agenda', 'datedeb_export', 'datefin_export');
		foreach ($toCheck as $elem){
			if (_request($elem)){
				if ($elem == 'datedeb_export' || $var == 'datefin_event')
	        $toUpdate->$elem = Kernel::_validDateProperties(_request($elem));
				else
					$toUpdate->$elem = _request($elem);
			}
		}
		$toUpdate->agenda = (_request("agenda")) ? _request("agenda") : array();

	}
	
}
?>
