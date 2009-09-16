<?php
/**
* Zone du module Agenda
* @package  Iconito
* @subpackage Agenda
* @author   Audrey Vassal
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require_once (COPIX_MODULE_PATH.'agenda/'.COPIX_CLASSES_DIR.'dateservices.class.php');
require_once (COPIX_MODULE_PATH.'agenda/'.COPIX_CLASSES_DIR.'agendaservices.class.php');

class ZoneAgendaToday extends CopixZone {
	function _createContent (&$toReturn) {
		
		$agendaService = new AgendaService;
		$serviceDate   = new DateService;
		
		//on d�termine le jour d'affichage
		if ($this->getParam('day') == null){					
			$day = date('Ymd');
			
		}else{
			$day = $this->getParam('day');
		}
		
		//on r�cup�re les �v�nements de la journ�e
		foreach($this->getParam('arAgendasAffiches') as $idAgenda){
			$arEventsSemaine[$idAgenda] = $agendaService->checkEventOfAgendaInBdd($idAgenda, $day, $day);
		}
		
		//on ordonne les �v�nements par ordre croissant d'heure de d�but d'�v�nement dans la journ�e
		$arEventByDay = $agendaService->getEventsByDay($arEventsSemaine, $day, $day);		
		$arEventByDay = $agendaService->getEventsInOrderByDay($arEventByDay);
				
		//on simplifie le tableau pour le passer � la zone
		$arDayEvent = $arEventByDay[$day]->events;
		//on r�cup�re la couleur d'affichage de chaque �v�nement
		//$arColorByEvent = $agendaService->getColorByIdEvent($arDayEvent);

		$arAgendas = $agendaService->getTilteAgendaByIdAgenda($this->getParam('arAgendasAffiches'));
		//on r�cup�re la couleur d'affichage pour chaque agenda
		$boolCroise = array();
		$daoAgenda = & CopixDAOFactory::getInstanceOf ('agenda|agenda');
		
		foreach($this->getParam('arAgendasAffiches') as $id){
			$agenda = $daoAgenda->get($id);
			$boolCroise[$agenda->type_agenda] = $boolCroise[$agenda->type_agenda] == false;
			$colors = $agendaService->getColorAgendaByIdAgenda($id);
			$arColorAgenda[$id] = $boolCroise[$agenda->type_agenda] ? $colors[0] : $colors[1];
		}
		$arEventToDisplay = array();
		foreach($arDayEvent as $event){
			$event->color = $arColorAgenda[$event->id_agenda];
			$arEventToDisplay[] = $event;
		}
		
		$jour  = substr($day,  6, 2);
		$mois  = $serviceDate->moisNumericToMoisLitteral(substr($day, 4, 2));
		$annee = substr($day,  0, 4);

		$tpl = & new CopixTpl ();
		
		$tpl->assign('jour' , $jour);
		$tpl->assign('mois' , $mois);
		$tpl->assign('annee', $annee);
		
		//$tpl->assign('arEvent'       , $arDayEvent);
		$tpl->assign('arEvent'       , $arEventToDisplay);
		$tpl->assign('arAgendas'     , $arAgendas);
		$tpl->assign('arColorByEvent', $arColorByEvent);
		$tpl->assign('arColorAgenda' , $arColorAgenda);
		
		$toReturn = $tpl->fetch ('aujourdhui.agenda.tpl');
		return true;
	}
}
?>
