<?php
/**
* @package Iconito
* @subpackage Agenda
* @author Audrey Vassal 
* @copyright 2001-2005 CopixTeam
* @link http://copix.org
* @licence http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude('agenda|agendaservices');
_classInclude('agenda|dateservices');

class ImportService {
	
	
	/**
	* Fonction qui ins�re en base de donn�es les �v�nements r�cup�r�s du fichier iCal
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/11 
	* @param array $pArEventsICal le tableau construit apr�s parse du fichier iCal
	* @param integer $pIdAgenda identifiant de l'agenda dans lequel doivent �tre ins�r�s les �v�nements
	* @return interger $nbEventsInseres nombre d'insertions effectu�es
	*/
	function importSansVider($pArEventsICal, $pIdAgenda){
		$serviceDate = new DateService;
		$nbEventsInseres = 0;
		foreach($pArEventsICal as $day=>$arEventsByDay){
			if(is_numeric($day) && strlen($day) == 8){//on �limine les premi�re case du tableau qui nous sont inutiles
				foreach($arEventsByDay as $key=>$arEvents){
					foreach($arEvents as $event){								
						if($event['event_text'] != null && $event['start_unixtime'] != null && $event['end_unixtime'] != null && $event['event_start'] != null && $event['event_end'] != null){
							$daoEvent = & CopixDAOFactory::getInstanceOf ('event');
							$record   = _record ('event');
							//on regarde si l'�v�nement � ins�rer existe d�j� dans l'agenda
							$criteres = _daoSp();
							$criteres->addCondition('id_agenda'     , '=', $pIdAgenda);
							$criteres->addCondition('title_event'   , '=', $event['event_text']);
							$criteres->addCondition('datedeb_event' , '=', date('Ymd', $event['start_unixtime']));
							$criteres->addCondition('datefin_event' , '=', date('Ymd', $event['end_unixtime']));
							$criteres->addCondition('heuredeb_event', '=', $serviceDate->heureWithoutSeparateurToheureWithSeparateur($event['event_start']));
							$criteres->addCondition('heurefin_event', '=', $serviceDate->heureWithoutSeparateurToheureWithSeparateur($event['event_end']));	
							$resultat = $daoEvent->findBy($criteres);
							if (count($resultat) > 0){//l'�v�nement existe, on passe au suivant
								break;								
							}
							
							else{//l'�v�nement n'existe pas dans l'agenda, on l'ins�re	
								$record->id_agenda        = $pIdAgenda;
								$record->title_event      = $event['event_text'];
								$record->desc_event       = $event['description'];
								$record->place_event      = $event['location'];
								$record->datedeb_event    = date('Ymd', $event['start_unixtime']);
								$record->datefin_event    = date('Ymd', $event['end_unixtime']);
								$record->heuredeb_event   = $serviceDate->heureWithoutSeparateurToheureWithSeparateur($event['event_start']);
								$record->heurefin_event   = $serviceDate->heureWithoutSeparateurToheureWithSeparateur($event['event_end']);
								$record->alldaylong_event = 0;
								$record->everyday_event   = 0;
								$record->everyweek_event  = 0;
								$record->everymonth_event = 0;
								$record->everyyear_event  = 0;
								$record->endrepeatdate_event = null;
								
								$daoEvent->insert ($record);	
								$nbEventsInseres = $nbEventsInseres + 1;//on incr�mente le nombre d'insertions
							}
						}
						else{ //cas des �v�nements qui ont lieu sur toute la journ�e
							
							if($event['event_text'] != null){
								$daoEvent = & CopixDAOFactory::getInstanceOf ('event');
								$record   = _record ('event');
								//on regarde si l'�v�nement � ins�rer existe d�j� dans l'agenda
								$criteres = _daoSp();
								$criteres->addCondition('id_agenda'     , '=', $pIdAgenda);
								$criteres->addCondition('title_event'   , '=', $event['event_text']);
								$criteres->addCondition('datedeb_event' , '=', $day);
								$resultat = $daoEvent->findBy($criteres);
								if (count($resultat) > 0){//l'�v�nement existe, on passe au suivant
									break;								
								}							
								else{
									$record->id_agenda        = $pIdAgenda;
									$record->title_event      = $event['event_text'];
									$record->desc_event       = $event['description'];
									$record->place_event      = $event['location'];
									$record->datedeb_event    = $day;
									$record->datefin_event    = $day;
									$record->alldaylong_event = 1;
									$record->everyday_event   = 0;
									$record->everyweek_event  = 0;
									$record->everymonth_event = 0;
									$record->everyyear_event  = 0;
									$record->endrepeatdate_event = null;
									
									$daoEvent->insert ($record);	
									$nbEventsInseres = $nbEventsInseres + 1;//on incr�mente le nombre d'insertions
								}
							}
						}
					}				
				}
			}
		}
		return $nbEventsInseres;
	}
	
	
	/**
	* Fonction qui vide les �v�nements en base sur la p�riode concern�e
	* En vidant l'agenda sur la p�riode concern�e
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/11 
	* @param array $pArEventsICal La date que l'on va incr�menter. Format Fr.
	* @param integer $pIdAgenda identifiant de l'agenda dans lequel doivent �tre ins�r�s les �v�nements
	* @return interger $nbEventsInseres nombre d'insertions effectu�es
	*/
	function viderBase ($pArEventsICal, $pIdAgenda){
		//on r�cup�re la date et heure de d�but

			print_r($pArEventsICal);

		foreach($pArEventsICal as $day=>$arEventsByDay){
			
			if(checkdate((int)substr($day, 4, 2), (int)substr($day, 6, 2), (int)substr($day, 0, 4))){
				foreach($arEventsByDay as $key=>$arEvents){
					foreach($arEvents as $event){
							$dateDeb = $day;
							$heureDeb = $event['event_start'];
					}
				}
				break;
			}
		}

		//on r�cup�re la date et heure de fin
		foreach($pArEventsICal as $day=>$arEventsByDay){			
			if(checkdate((int)substr($day, 4, 2), (int)substr($day, 6, 2), (int)substr($day, 0, 4))){
				foreach($arEventsByDay as $key=>$arEvents){
					foreach($arEvents as $event){
							$dateFin = $day;
							$heureFin = $event['event_end'];
					}
				}
			}
		}		
	
		//on r�cup�re tous les �v�nements de l'agenda sur la p�riode concern�e
		$serviceAgenda = new AgendaService;
		$serviceDate   = new DateService;
		$arEventsInBdd = $serviceAgenda->checkEventOfAgendaInBdd($pIdAgenda, $dateDeb, $dateFin);
		
		//voir quels agendas on prend quand on vide sur la p�riode
		
		//on vide l'agenda sur la p�riode concern�e
		$daoEvent = & CopixDAOFactory::getInstanceOf ('event');
		foreach((array)$arEventsInBdd as $event){
			//cas d'un �v�nement qui ne se r�p�te pas
			if($event->endrepeatdate_event == null){				
				if(($event->datefin_event > $dateDeb && $event->datedeb_event < $dateFin) || 
				   ($event->datedeb_event == $dateDeb && $serviceDate->heureWithSeparateurToheureWithoutSeparateur($event->heurefin_event) > $heureDeb && $serviceDate->heureWithSeparateurToheureWithoutSeparateur($event->heuredeb_event) < $heureFin) || 
				   ($event->datefin_event == $dateFin && $serviceDate->heureWithSeparateurToheureWithoutSeparateur($event->heuredeb_event) < $heureFin && $serviceDate->heureWithSeparateurToheureWithoutSeparateur($event->heurefin_event) > $heureDeb) || 
				   ($event->datefin_event == $dateFin && $event->alldaylong_event == 1) || 
				   ($event->datedeb_event == $dateDeb && $event->alldaylong_event == 1)){
						$daoEvent->delete($event->id_event);
				}
			}
			
			//l'�v�nement � supprimer se r�p�te, on d�coupe l'�v�nement en 2 parties :
			//un �v�nement avant la p�riode d'insertion, un �v�nement apr�s le p�riode d'insertion
			else{
				$eventDuplicate = $event;//duplication de l'�v�nement pour garder les infos de base			
				$record = _record ('event');

				$criteres = _daoSp();
				$criteres->addCondition('id_event', '=', $event->id_event);	
				$resultat = $daoEvent->findBy($criteres);
				
				//on modifie la date de fin de r�p�tition de l'�v�nement
				if (count($resultat) > 0){
					$record = $resultat[0];
					if($serviceDate->heureWithSeparateurToheureWithoutSeparateur($record->heurefin_event) < $heureDeb && $record->alldaylong_event == 0){
						$record->endrepeatdate_event = $dateDeb;
					}
					if($serviceDate->heureWithSeparateurToheureWithoutSeparateur($record->heurefin_event) > $heureDeb || $record->alldaylong_event == 1){
						$record->endrepeatdate_event = $serviceDate->retireUnJour($dateDeb);
					}
					$daoEvent->update ($record);
				}				
								
				//on cr�e un autre �v�nement qui commence apr�s la p�riode concern�e
				//si il se poursuivait apr�s cette p�riode
				if($eventDuplicate->endrepeatdate_event > $dateFin || ($eventDuplicate->endrepeatdate_event == $dateFin && $eventDuplicate->heuredeb_event >= $heureFin)){				
					$record = _record ('event');
					if($eventDuplicate->everyday_event == 1){
						if($serviceDate->heureWithSeparateurToheureWithoutSeparateur($eventDuplicate->heuredeb_event) < $heureFin || $eventDuplicate->alldaylong_event == 1){
							//les heures se chevauchent, on va au jour suivant
							$datedebEvent = $dateFin;
							$datedebEvent = $serviceDate->dateBddToDateFr($datedebEvent);
							$datedebEvent = $serviceDate->addToDate($datedebEvent, 1, 0, 0);
							$record->datedeb_event = $serviceDate->dateFrToDateBdd($datedebEvent);
							$nbJour = $serviceDate->getNombreJoursEcoulesEntreDeuxDates($eventDuplicate->datedeb_event, $eventDuplicate->datedeb_event);
							$record->datefin_event = $serviceDate->dateFrToDateBdd($serviceDate->addToDate($serviceDate->dateBddToDateFr($record->datedeb_event), $nbJour, 0, 0));
						}
						if($serviceDate->heureWithSeparateurToheureWithoutSeparateur($eventDuplicate->heuredeb_event) > $heureFin && $eventDuplicate->alldaylong_event == 0){
							$datedebEvent = $dateFin;
							$record->datedeb_event = $datedebEvent;
							$nbJour = $serviceDate->getNombreJoursEcoulesEntreDeuxDates($eventDuplicate->datedeb_event, $eventDuplicate->datedeb_event);
							$record->datefin_event = $serviceDate->dateFrToDateBdd($serviceDate->addToDate($serviceDate->dateBddToDateFr($record->datedeb_event), $nbJour, 0, 0));
						}
					}
					if($eventDuplicate->everyweek_event == 1){
						//le jour de d�but d'�v�nement est le jour de fin de p�riode
						if(date('w', $serviceDate->dateAndHoureBdToTimestamp($eventDuplicate->datedeb_event, null)) == date('w', $serviceDate->dateAndHoureBdToTimestamp($dateFin, null))){
							if($serviceDate->heureWithSeparateurToheureWithoutSeparateur($eventDuplicate->heuredeb_event) < $heureFin || $eventDuplicate->alldaylong_event == 1){
								//les heures se chevauchent, on va au jour de la semaine suivante
								$datedebEvent = $dateFin;
								$datedebEvent = $serviceDate->dateBddToDateFr($datedebEvent);
								$datedebEvent = $serviceDate->addToDate($datedebEvent, 7, 0, 0);
								$record->datedeb_event = $serviceDate->dateFrToDateBdd($datedebEvent);
								$nbJour = $serviceDate->getNombreJoursEcoulesEntreDeuxDates($eventDuplicate->datedeb_event, $eventDuplicate->datedeb_event);
								$record->datefin_event = $serviceDate->dateFrToDateBdd($serviceDate->addToDate($serviceDate->dateBddToDateFr($record->datedeb_event), $nbJour, 0, 0));
							}
							if($serviceDate->heureWithSeparateurToheureWithoutSeparateur($eventDuplicate->heuredeb_event) > $heureFin && $eventDuplicate->alldaylong_event == 0){
								$datedebEvent = $dateFin;
								$record->datedeb_event = $datedebEvent;
								$nbJour = $serviceDate->getNombreJoursEcoulesEntreDeuxDates($eventDuplicate->datedeb_event, $eventDuplicate->datedeb_event);
								$record->datefin_event = $serviceDate->dateFrToDateBdd($serviceDate->addToDate($serviceDate->dateBddToDateFr($record->datedeb_event), $nbJour, 0, 0));
							}
						}
						else{
							$datedebEvent = $dateFin;
							$record->datedeb_event = $serviceDate->getDayOfWeekAfterDate($datedebEvent, date('w', $serviceDate->dateAndHoureBdToTimestamp($eventDuplicate->datedeb_event, null)));
							$nbJour = $serviceDate->getNombreJoursEcoulesEntreDeuxDates($eventDuplicate->datedeb_event, $eventDuplicate->datedeb_event);
							$record->datefin_event = $serviceDate->dateFrToDateBdd($serviceDate->addToDate($serviceDate->dateBddToDateFr($record->datedeb_event), $nbJour, 0, 0));
						}
						
					}
					if($eventDuplicate->everymonth_event == 1){
						//le jour de d�but d'�v�nement est le jour de fin de p�riode
						if(date('md', $serviceDate->dateAndHoureBdToTimestamp($eventDuplicate->datedeb_event, null)) == date('md', $serviceDate->dateAndHoureBdToTimestamp($dateFin, null))){
							if($serviceDate->heureWithSeparateurToheureWithoutSeparateur($eventDuplicate->heuredeb_event) < $heureFin || $eventDuplicate->alldaylong_event == 1){
								//les heures se chevauchent, on va au jour du mois suivant
								$datedebEvent = $dateFin;
								$datedebEvent = $serviceDate->dateBddToDateFr($datedebEvent);
								$datedebEvent = $serviceDate->addToDate($datedebEvent, 0, 1, 0);
								$record->datedeb_event = $serviceDate->dateFrToDateBdd($datedebEvent);
								$nbJour = $serviceDate->getNombreJoursEcoulesEntreDeuxDates($eventDuplicate->datedeb_event, $eventDuplicate->datedeb_event);
								$record->datefin_event = $serviceDate->dateFrToDateBdd($serviceDate->addToDate($serviceDate->dateBddToDateFr($record->datedeb_event), $nbJour, 0, 0));
							}
							if($serviceDate->heureWithSeparateurToheureWithoutSeparateur($eventDuplicate->heuredeb_event) > $heureFin && $eventDuplicate->alldaylong_event == 0){
								$datedebEvent = $dateFin;
								$record->datedeb_event = $datedebEvent;
								$nbJour = $serviceDate->getNombreJoursEcoulesEntreDeuxDates($eventDuplicate->datedeb_event, $eventDuplicate->datedeb_event);
								$record->datefin_event = $serviceDate->dateFrToDateBdd($serviceDate->addToDate($serviceDate->dateBddToDateFr($record->datedeb_event), $nbJour, 0, 0));
							}
						}
						else{
							$datedebEvent = $dateFin;
							$record->datedeb_event = $serviceDate->getDayOfMonthAfterDate($datedebEvent, substr($eventDuplicate->datedeb_event, 6, 2));
							$nbJour = $serviceDate->getNombreJoursEcoulesEntreDeuxDates($eventDuplicate->datedeb_event, $eventDuplicate->datedeb_event);
							$record->datefin_event = $serviceDate->dateFrToDateBdd($serviceDate->addToDate($serviceDate->dateBddToDateFr($record->datedeb_event), $nbJour, 0, 0));
						}
					}
					if($eventDuplicate->everyyear_event == 1){
						//le jour de d�but d'�v�nement est le jour de fin de p�riode
						if(date('Ymd', $serviceDate->dateAndHoureBdToTimestamp($eventDuplicate->datedeb_event, null)) == date('Ymd', $serviceDate->dateAndHoureBdToTimestamp($dateFin, null))){
							if($serviceDate->heureWithSeparateurToheureWithoutSeparateur($eventDuplicate->heuredeb_event) < $heureFin || $eventDuplicate->alldaylong_event == 1){
								//les heures se chevauchent, on va au jour de l'ann�e suivante
								$datedebEvent = $dateFin;
								$datedebEvent = $serviceDate->dateBddToDateFr($datedebEvent);
								$datedebEvent = $serviceDate->addToDate($datedebEvent, 0, 0, 1);
								$record->datedeb_event = $serviceDate->dateFrToDateBdd($dateFin);
								$nbJour = $serviceDate->getNombreJoursEcoulesEntreDeuxDates($eventDuplicate->datedeb_event, $eventDuplicate->datedeb_event);
								$record->datefin_event = $serviceDate->dateFrToDateBdd($serviceDate->addToDate($serviceDate->dateBddToDateFr($record->datedeb_event), $nbJour, 0, 0));
							}
							if($serviceDate->heureWithSeparateurToheureWithoutSeparateur($eventDuplicate->heuredeb_event) > $heureFin && $eventDuplicate->alldaylong_event == 0){
								$datedebEvent = $dateFin;
								$record->datedeb_event = $datedebEvent;
								$nbJour = $serviceDate->getNombreJoursEcoulesEntreDeuxDates($eventDuplicate->datedeb_event, $eventDuplicate->datedeb_event);
								$record->datefin_event = $serviceDate->dateFrToDateBdd($serviceDate->addToDate($serviceDate->dateBddToDateFr($record->datedeb_event), $nbJour, 0, 0));
							}
						}
						else{
							$datedebEvent = $dateFin;
							$record->datedeb_event = $serviceDate->getDayOfYearAfterDate($datedebEvent, substr($eventDuplicate->datedeb_event, 4, 4));
							$nbJour = $serviceDate->getNombreJoursEcoulesEntreDeuxDates($eventDuplicate->datedeb_event, $eventDuplicate->datedeb_event);
							$record->datefin_event = $serviceDate->dateFrToDateBdd($serviceDate->addToDate($serviceDate->dateBddToDateFr($record->datedeb_event), $nbJour, 0, 0));
						}
					}				
					$record->id_agenda            = $eventDuplicate->id_agenda;
					$record->title_event          = $eventDuplicate->title_event;
					$record->desc_event           = $eventDuplicate->desc_event;
					$record->place_event          = $eventDuplicate->place_event;
					$record->heuredeb_event       = $eventDuplicate->heuredeb_event;
					$record->heurefin_event       = $eventDuplicate->heurefin_event;
					$record->alldaylong_event     = $eventDuplicate->alldaylong_event;
					$record->everyday_event       = $eventDuplicate->everyday_event;
					$record->everyweek_event      = $eventDuplicate->everyweek_event;
					$record->everymonth_event     = $eventDuplicate->everymonth_event;
					$record->everyyear_event      = $eventDuplicate->everyyear_event;
					$record->endrepeatdate_event  = $eventDuplicate->endrepeatdate_event;
					
					$daoEvent->insert ($record);
				}
			}
		}
	}
}
?>
