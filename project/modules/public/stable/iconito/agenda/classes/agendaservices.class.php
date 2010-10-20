<?php
/**
 * 
 * @package Iconito
 * @subpackage Agenda
 * @author Audrey Vassal 
 * @copyright 2001-2005 CopixTeam
 * @link http://copix.org
 * @licence http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

_classInclude('agenda|agendaauth');
_classInclude('agenda|dateservices');
_classInclude('agenda|agendatype');

class AgendaService {
	
	/**
	* R�cup�ration de tous les agendas en base de donn�es 
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/07/24
	* @return array tableau d'objet agenda
	*/
    function getAvailableAgenda (){
			
			//var_dump($_SESSION);
			
			
      if (!_sessionGet ('modules|agenda|his')) {
				$serviceAuth   = new AgendaAuth;
				
				$res = array();
				
				$ags = array();
				
				// 1. Son agenda perso
				$userInfo = Kernel::getUserInfo();
				// Cr�ation des modules inexistants.
				Kernel::createMissingModules( $userInfo["type"], $userInfo["id"] );
				// Liste des modules activ�s.
				$modsList = Kernel::getModEnabled( $userInfo["type"], $userInfo["id"] );
				foreach ($modsList AS $modInfo) {
					if( $modInfo->module_type == "MOD_AGENDA" && $modInfo->module_id) {
						$ags[] = $modInfo->module_id;
					}
				}
			
				// 2. Ceux de sa classe, son �cole, ses groupes...
				$mynodes = Kernel::getNodes();
				foreach ($mynodes as $nodes) {
					foreach ($nodes as $node) {
						//print_r($node);
            if (substr($node['type'],0,5)=='USER_') continue;
						$modules = Kernel::getModEnabled ($node['type'], $node['id']);
						$agenda = Kernel::filterModuleList ($modules, 'MOD_AGENDA');
						if ($agenda && $serviceAuth->getCapability($agenda[0]->module_id) >= $serviceAuth->getRead())
							$ags[] = $agenda[0]->module_id;
					}	
				}
				//print_r($ags);
        $daoAgenda = &CopixDAOFactory::getInstanceOf ('agenda|agenda');
				$agendas = $daoAgenda->findAgendasInIds($ags);
				
				foreach ($agendas as $agenda) {
          $tmp = new stdClass();
          $tmp->id_agenda = $agenda->id_agenda;
          $tmp->title_agenda = $agenda->title_agenda;
          $tmp->desc_agenda = $agenda->desc_agenda;
          $tmp->type_agenda = $agenda->type_agenda;
          /*
					$tmp = array (
						'id_agenda' => $agenda->id_agenda,
						'title_agenda' => $agenda->title_agenda,
						'desc_agenda' => $agenda->desc_agenda,
						'type_agenda' => $agenda->type_agenda,
					);
          */
					$res[] = $tmp;
				}
        //die();
				//$sess = $daoAgenda->findAll ();
				_sessionSet ('modules|agenda|his', serialize($res));
			}
			
			return unserialize(_sessionGet ('modules|agenda|his'));
    }
	
	
	/**
	* Stock en session la liste des identifiants des agendas � afficher
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/07/28
	* @param array $pArIdAgenda tableau d'identifiant des �l�ments � afficher
	*/
	function setAgendaAffiches ($pArIdAgenda){
		_sessionSet ('modules|agenda|affiches', $pArIdAgenda);
	}
	
	
	/**
	* R�cup�re de la session la liste des agendas � afficher
	* Si rien en session, revoie l'agenda personnel
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/07/28
	* @return array $arAgendaAffiches agendas � afficher
	*/
	function getAgendaAffiches (){

		if (_sessionGet ('modules|agenda|affiches')){
	   	return (_sessionGet ('modules|agenda|affiches'));
		}else{
			$listAgendas = AgendaService::getAvailableAgenda();
      //print_r($listAgendas);
			$arAgendaAffiches = array();
			foreach($listAgendas as $agenda){
				if($agenda->type_agenda == AgendaType::getPersonnal()){
					$arAgendaAffiches[$agenda->id_agenda] = $agenda->id_agenda;
				}
			}
			AgendaService::setAgendaAffiches($arAgendaAffiches);
         return $arAgendaAffiches;
		}
	}
	
	
	/**
	* Renvoie un tableau de titre d'agendas
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/07
	* @param array $pArIdAgenda tableau d'identifiants d'agenda
	* @return array $ArTilteAgenda tableau de titre d'agendas
	*/
	function getArTitleAgendaByArIdAgenda($pArIdAgenda){
		$daoAgenda = &CopixDAOFactory::getInstanceOf ('agenda|agenda');
		if(count($pArIdAgenda)>0){
			foreach($pArIdAgenda as $id){
				$daoSearchParams = _daoSp ();
				$daoSearchParams->addCondition ('id_agenda', '=', $id);
				$arAgenda = $daoAgenda->findBy ($daoSearchParams);
				if(count($arAgenda)>0){
					$ArTilteAgenda[$id] = $arAgenda[0]->title_agenda;
				}				
			}
		}
		return $ArTilteAgenda;
	}
	
		
	/**
	* Indique la date fin de fin de r�p�tition quand l'utilisateur demande � r�p�ter un certain nbe de fois
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/07/26
	* @param integer $pNbFois nombre de fois que l'�v�nement est r�p�t�
	* @param integer $pFrequence fr�quence � laquelle l'�v�nement est r�p�t� (jour, semaine, mois, annee)
	* @param date $pDateDebutEvent $pDateDebutEvent date du d�but de l'�v�nement, format JJ/MM/AAAA
	* @return date (au format yyyymmdd) $dateFinEvent date � laquelle se termine l'�v�nement
	*/
    function getDateEndRepeatByNbFois ($pNbFois, $pFrequence, $pDateDebutEvent){ 
			//var_dump($pDateDebutEvent);
			//echo "getDateEndRepeatByNbFois ($pNbFois, $pFrequence, $pDateDebutEvent)";
			
      $serviceDate = new DateService;
			
			/*
			$dateI18N = CopixDateTime::dateToTimestamp ($pDateDebutEvent);
			$date2 = $serviceDate->dateBddToDateFr($dateI18N);
			*/
			$date2 = $pDateDebutEvent;

			//var_dump($dateI18N);
			//var_dump($date2);
		
				// Ev�nement se r�p�te tous les jours
        if ($pFrequence == 'everyday_event') {
            $dateFinEvent = $serviceDate->addToDate ($date2, $pNbFois, 0, 0, '/');
        } 
        // Ev�nement se r�p�te toutes les semaines
        if ($pFrequence == 'everyweek_event') {
            $dateFinEvent = $serviceDate->addToDate ($date2, $pNbFois * 7, 0, 0, '/');
        } 
        // Ev�nement se r�p�te tous les mois
        if ($pFrequence == 'everymonth_event') {
            $dateFinEvent = $serviceDate->addToDate ($date2, 0, $pNbFois, 0, '/');
        } 
        // Ev�nement se r�p�te toutes les ann�es
        if ($pFrequence == 'everyyear_event') {
            $dateFinEvent = $serviceDate->addToDate ($date2, 0, 0, $pNbFois, '/');
        } 
				
				//var_dump($dateFinEvent);
        return $dateFinEvent;
    } 

	
	/*
	* Fonction qui r�cup�re tous les �v�nements de l'agenda affich�, � la semaine affich�e
	* @param  integer $pIdAgenda identifiant de l'agenda concern�
	* @param  integer $pDateDeb date de d�but de la semaine affich�e au format yyyymmdd
	* @param  integer $pDateFin date de fin de la semaine affich�e au format yyyymmdd
	* @return array $arResultat tableau d'�v�nements
	*/
    function checkEventOfAgendaInBdd ($pIdAgenda, $pDateDeb, $pDateFin){
	
		$query = 'SELECT * FROM module_agenda_event where id_agenda = ' . $pIdAgenda . '
					AND (((datefin_event >= ' . $pDateDeb . ') OR (endrepeatdate_event >= ' . $pDateDeb . '))
						AND (datedeb_event <= ' . $pDateFin . '))' ;
						
        $result = _doQuery($query);
				
				$arResultat = array();
				foreach ($result as $r) 
	        $arResultat[] = $r;
        return $arResultat;
    } 

	
	/**
	* Fonction qui r�cup�re les le�ons en base pour une p�riode donn�e et les classe par jour
	* @param array $pArAgendas Tableau des agendas concernes
	* @param date $pDateDeb date de d�but de la p�riode au format yyyymmdd
	* @param date $pDateFin date de fin de la p�riode au format yyyymmdd
	* @return array $arLeconsByDays les le�ons de la p�riode class�es par jour
	*/
	function getLeconsByDay($pArAgendas, $pDateDeb, $pDateFin){
		$dateService  = new DateService;		
		$dateCourante = $pDateDeb; 
		$daoLecon     = & CopixDAOFactory::getInstanceOf ('lecon');
		
		while($dateCourante <= $pDateFin){
			
			$sql = "SELECT LEC.* FROM module_agenda_lecon LEC WHERE LEC.date_lecon='".$dateCourante."' AND LEC.id_agenda IN (".implode(',',$pArAgendas).")";
			$resultat = _doQuery($sql);
			
			if (count($resultat) > 0){//modification
				$arLeconsByDays[$dateCourante] = $resultat[0];
			}
			else{
				$arLeconsByDays[$dateCourante] = null;
			}
			
			//on incr�mente le nombre de jours de 1 � chaque passage
			$dateCourante = $dateService->addToDate($dateService->dateBddToDateFr($dateCourante), 1, 0, 0, '/');
			$dateCourante = $dateService->dateFrToDateBdd($dateCourante);			
		}		
		return $arLeconsByDays;
	}
	
	
	/**
	* Fonction qui organise par jour, les �l�ments ayant lieu dans p�riode donn�e
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/03
	* @param array $arEventsSemaine tous les �v�nements ayant lieu dans la semaine
	* @param array $dateDebutSemaine date de d�but de la semaine au format yyyymmdd
	* @param array $dateFinSemaine date de fin de la semaine au format yyyymmdd
	* @return array $arEventByDay les �v�nement de la semaine class�s par jour
	*/
	function getEventsByDay($arEventsSemaine, $dateDebutSemaine, $dateFinSemaine){
		$dateCourante = $dateDebutSemaine;
		$dateService = new DateService;
		$noEvent = true;//variable � true s'il n'y a pas d'�v�nements dans la semaine
		$arEventByDay = array();
		
		while($dateCourante <= $dateFinSemaine){
			foreach($arEventsSemaine as $idAgenda=>$arEvents){
				foreach((array)$arEvents as $event){
				$noEvent = false;
						if($event->endrepeatdate_event == null){//cas des �v�nements qui ne se r�p�tent pas
							if($event->datedeb_event == $event->datefin_event){//l'�v�nement se d�roule enti�rement dans la m�me journ�e
								if(($dateCourante >= $event->datedeb_event) && ($event->datefin_event >= $dateCourante)){
									$arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
								}
							}
							else{//l'�v�nement se d�roule sur plusieurs jours
								$eventDuplicate = $event;//on copie l'�v�nement pour travailler dessus
								if($dateCourante == $event->datedeb_event && $dateCourante < $event->datefin_event){//premier jour de l'�v�nement
									$eventDuplicate->heurefin_event = '24:00';
									$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
								}
								if($dateCourante > $event->datedeb_event && $dateCourante < $event->datefin_event){
									$eventDuplicate->heuredeb_event = '00:00';
									$eventDuplicate->heurefin_event = '24:00';
									$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
								}
								if($dateCourante > $event->datedeb_event && $dateCourante == $event->datefin_event){//dernier jour de l'�v�nement
									$eventDuplicate->heuredeb_event = '00:00';
									$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
								}
							}
						}
						else{//cas des �v�nements qui se r�p�tent
							//si la date de fin de r�p�tition se situe en plein dans l'�v�nement, on ne commence pas l'�v�nement
							$nbJours = $dateService->getNombreJoursEcoulesEntreDeuxDates($event->datefin_event, $event->datedeb_event);			
							$dateCourantePlusNbJours = $dateService->dateBddToDateFr($dateCourante);
							$dateCourantePlusNbJours = $dateService->addToDate($dateCourantePlusNbJours, $nbJours, 0, 0);
							$dateCourantePlusNbJours = $dateService->dateFrToDateBdd($dateCourantePlusNbJours);							
							//�v�nement qui se r�p�te tous les jours
							if($event->everyday_event == 1 && $dateCourantePlusNbJours <= $event->endrepeatdate_event && $event->datedeb_event <= $dateCourante){
								if($event->datedeb_event == $event->datefin_event){//l'�v�nement se d�roule enti�rement dans la m�me journ�e
									$arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
								}
								else{//l'�v�nement se d�roule sur plusieurs jours
									$eventDuplicate = $event;//on copie l'�v�nement pour travailler dessus
									if($dateCourante == $event->datedeb_event && $dateCourante < $event->datefin_event && $dateCourante < $event->endrepeatdate_event){//premier jour de l'�v�nement
										$eventDuplicate->heurefin_event = '24:00';
										$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
									}
									else if($dateCourante > $event->datedeb_event && $dateCourante == $event->endrepeatdate_event){//dernier jour de l'�v�nement
										$eventDuplicate->heuredeb_event = '00:00';
										$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
									}
									else{
										//fin de l'�v�nement de la journ�e pr�c�dente
										$eventDuplicate->heuredeb_event = '00:00';
										$eventDuplicate->heurefin_event = $event->heurefin_event;
										$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
										//d�but de l'�v�nement
										$eventDuplicate->heuredeb_event = $event->heuredeb_event;
										$eventDuplicate->heurefin_event = '24:00';
										$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
									}									
								}
							}
							if($event->everyweek_event == 1 && $dateCourantePlusNbJours <= $event->endrepeatdate_event && $event->datedeb_event <= $dateCourante){		
								$dateCouranteTimestamp = $dateService->dateAndHoureBdToTimestamp($dateCourante, null);
								$jourCourant           = date('w', $dateCouranteTimestamp);
								$jourDebutEvent        = date('w', $dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null));
								$jourFinEvent          = date('w', $dateService->dateAndHoureBdToTimestamp($event->datefin_event, null));
								if($event->datedeb_event == $event->datefin_event){//l'�v�nement se d�roule enti�rement dans la m�me journ�e
									//si l'�v�nement se d�roule enti�rement dans la m�me semaine
									if($dateService->dateToWeeknum($dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) == $dateService->dateToWeeknum($dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){
										if(($jourDebutEvent-1 <= $jourCourant-1) && ($jourCourant-1 <= $jourFinEvent-1)){
											$arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
										}
									}
									else{//l'�v�nement commence en fin de semaine et se termine en d�but de semaine suivante
										if(($jourCourant <= $jourFinEvent) || ($jourCourant == $jourDebutEvent)){
											$arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
										}
									}
								}
								else{//l'�v�nement se d�roule sur plusieurs jours
									$eventDuplicate = $event;//on copie l'�v�nement pour travailler dessus
									if($jourCourant == $jourDebutEvent){//premier jour de l'�v�nement
										$eventDuplicate->heurefin_event = '24:00';
										$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
									}
									else if($jourCourant == $jourFinEvent){//dernier jour de l'�v�nement
										$eventDuplicate->heuredeb_event = '00:00';
										$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
									}
									//l'�v�nement se d�roule tout dans la m�me semaine
									else if($dateService->dateToWeeknum($dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) == $dateService->dateToWeeknum($dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){
										if($jourDebutEvent < $jourCourant && $jourCourant < $jourFinEvent){
											$eventDuplicate->heuredeb_event = '00:00';
											$eventDuplicate->heurefin_event = '24:00';
											$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
										}
									}
									//l'�v�nement commence en fin de semaine et se termine en d�but de semaine suivante
									else if($dateService->dateToWeeknum($dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) != $dateService->dateToWeeknum($dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){
										if ($jourCourant < $jourFinEvent){
										$eventDuplicate->heuredeb_event = '00:00';
										$eventDuplicate->heurefin_event = '24:00';
										$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
										}
									}									
								}							
							}
							//on ne compare que les jours pour un �v�nement qui se r�p�te tous les mois
							if($event->everymonth_event == 1 && $dateCourantePlusNbJours <= $event->endrepeatdate_event && $event->datedeb_event <= $dateCourante){
								if($event->datedeb_event == $event->datefin_event){//l'�v�nement se d�roule enti�rement dans la m�me journ�e
									//si l'�v�nement se d�roule enti�rement dans le m�me mois
									if(date('m', $dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) == date('m', $dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){
										if((substr($event->datedeb_event, 6, 2) <= substr($dateCourante, 6, 2)) && (substr($dateCourante, 6, 2) <= substr($event->datefin_event, 6, 2))){
											$arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
										}
									}
									else{//l'�v�nement commence � la fin d'un mois et se termine au d�but du mois suivant
										if(( (substr($event->datedeb_event, 6, 2) <= substr($dateCourante, 6, 2)) &&  (substr($dateCourante, 6, 2) <= 31) ) || ( (1 <= substr($dateCourante, 6, 2)) && (substr($dateCourante, 6, 2) <= substr($event->datefin_event, 6, 2)) )){
											$arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
										}
									}
								}
								else{//l'�v�nement se d�roule sur plusieurs jours
									$eventDuplicate = $event;//on copie l'�v�nement pour travailler dessus
									if(substr($event->datedeb_event, 6, 2) == substr($dateCourante, 6, 2)){//premier jour de l'�v�nement									
										$eventDuplicate->heurefin_event = '24:00';
										$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
									}
									else if(substr($event->datefin_event, 6, 2) == substr($dateCourante, 6, 2)){//dernier jour de l'�v�nement
										$eventDuplicate->heuredeb_event = '00:00';
										$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
									}									
									else if(date('m', $dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) == date('m', $dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){//l'�v�nement se d�roule enti�rement dans le m�me mois
										if((substr($event->datedeb_event, 6, 2) < substr($dateCourante, 6, 2)) && (substr($event->datefin_event, 6, 2) > substr($dateCourante, 6, 2))){
											$eventDuplicate->heuredeb_event = '00:00';
											$eventDuplicate->heurefin_event = '24:00';
											$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
										}
									}
									else if(date('m', $dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) != date('m', $dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){//l'�v�nement commence � la fin d'un mois et se termine au d�but du mois suivant
										if(( (substr($event->datedeb_event, 6, 2) < substr($dateCourante, 6, 2)) &&  (substr($dateCourante, 6, 2) <= 31) ) || ( (1 <= substr($dateCourante, 6, 2)) && (substr($dateCourante, 6, 2) < substr($event->datefin_event, 6, 2)))){
											$eventDuplicate->heuredeb_event = '00:00';
											$eventDuplicate->heurefin_event = '24:00';
											$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
										}
									}
								}
							}
							//on ne compare que les jours et mois pour un �v�nement qui se r�p�te toutes les ann�es
							if($event->everyyear_event == 1 && $dateCourantePlusNbJours <= $event->endrepeatdate_event && $event->datedeb_event <= $dateCourante){
								if($event->datedeb_event == $event->datefin_event){//l'�v�nement se d�roule enti�rement dans la m�me journ�e
									//si l'�v�nement se d�roule enti�rement dans la m�me ann�e
									if(date('Y', $dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) == date('Y', $dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){
										if((substr($event->datedeb_event, 4, 4) <= substr($dateCourante, 4, 4)) && (substr($dateCourante, 4, 4) <= substr($event->datefin_event, 4, 4))){
											$arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
										}
									}
									else{//l'�v�nement commence � la fin d'une ann�e et se termine au d�but de l'ann�e suivante
										if(( (substr($event->datedeb_event, 4, 4) <= substr($dateCourante, 4, 4)) &&  (substr($dateCourante, 4, 4) <= 1231) ) || ( (101 <= substr($dateCourante, 4, 4)) && (substr($dateCourante, 4, 4) <= substr($event->datefin_event, 4, 4)) )){
											$arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
										}
									}
								}
								else{//l'�v�nement se d�roule sur plusieurs jours
									$eventDuplicate = $event;//on copie l'�v�nement pour travailler dessus
									if(substr($event->datedeb_event, 4, 4) == substr($dateCourante, 4, 4)){//premier jour de l'�v�nement									
										$eventDuplicate->heurefin_event = '24:00';
										$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
									}
									else if(substr($event->datefin_event, 4, 4) == substr($dateCourante, 4, 4)){//dernier jour de l'�v�nement
										$eventDuplicate->heuredeb_event = '00:00';
										$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
									}
									//si l'�v�nement se d�roule enti�rement dans la m�me ann�e
									else if(date('Y', $dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) == date('Y', $dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){
										if((substr($event->datedeb_event, 4, 4) < substr($dateCourante, 4, 4)) && (substr($event->datefin_event, 4, 4) > substr($dateCourante, 4, 4))){
											$eventDuplicate->heuredeb_event = '00:00';
											$eventDuplicate->heurefin_event = '24:00';
											$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
										}							
									}
									else if(date('Y', $dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) != date('Y', $dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){//l'�v�nement commence � la fin d'une ann�e et se termine au d�but de l'ann�e suivante
										if(((substr($event->datedeb_event, 4, 4) < substr($dateCourante, 4, 4)) &&  (substr($dateCourante, 4, 4) <= 1231) ) || ( (101 <= substr($dateCourante, 4, 4)) && (substr($dateCourante, 4, 4) < substr($event->datefin_event, 4, 4)))){
											$eventDuplicate->heuredeb_event = '00:00';
											$eventDuplicate->heurefin_event = '24:00';
											$arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
										}
									}
								}
							}
						}
					//si pas d'�v�nement ce jour l�
					if(!isset($arEventByDay[$dateCourante]) || count($arEventByDay[$dateCourante]) == 0){
						$arEventByDay[$dateCourante] = null;						
					}
				}
			}
			
			//on incr�mente le nombre de jours de 1 � chaque passage
			$dateCourante = $dateService->addToDate($dateService->dateBddToDateFr($dateCourante), 1, 0, 0, '/');
			$dateCourante = $dateService->dateFrToDateBdd($dateCourante);
		}
		
		//si pas d'�v�nements de la semaine, ne passe pas dans le foreach
		//donc on construit un tableau vide pour pouvoir ins�rer les cases transparentes par la suite		
		if($noEvent == true){
			$dateCourante = $dateDebutSemaine;
			while($dateCourante <= $dateFinSemaine){			
				$arEventByDay[$dateCourante] = null;			
				//on incr�mente le nombre de jours de 1 � chaque passage
				$dateCourante = $dateService->addToDate($dateService->dateBddToDateFr($dateCourante), 1, 0, 0, '/');
				$dateCourante = $dateService->dateFrToDateBdd($dateCourante);
			}
		}	
		return $arEventByDay;
	}
	
	/**
	* Fonction qui ordonne les �v�nements par heure de d�but
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/03
	* @param array $pArEventByDay tableau d'�v�nements class�s par jour
	* @return array $pArEventByDay tableau des �v�nements ordonn�s par heure de d�but
	*/	
	function getEventsInOrderByDay($pArEventByDay){
		foreach((array)$pArEventByDay as $date=>$jour){		
			if($pArEventByDay[$date] != null){
				ksort($pArEventByDay[$date]->events);
			}
		}
		return $pArEventByDay;
	}
	
	/**
	* Fonction qui donne le type de l'agenda
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/03
	* @param integer $pIdAgenda l'identifiant de l'agenda
	* @return integer $typeAgenda l'identifiant du type de l'agenda
	*/	
	function getTypeAgendaByIdAgenda($pIdAgenda){
		
		$daoSearchParams = _daoSp ();
		$daoSearchParams->addCondition ('id_agenda', '=', $pIdAgenda);

		$daoAgenda = & CopixDAOFactory::getInstanceOf ('agenda|agenda');
		$arAgenda  = $daoAgenda->findBy ($daoSearchParams);
		if(count($arAgenda)>0){
			$typeAgenda = $arAgenda[0]->type_agenda;
		}
		else{
			$typeAgenda = AgendaType::getOthers();
		}
		return $typeAgenda;
	}
	
	/**
	* Fonction qui retourne un tableau associant l'identifiant de l'�v�nement et sa couleur d'affichage
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/08
	* @param integer $pIdAgenda l'identifiant de l'agenda
	* @return array $arColor tableau de code ascii de la couleur
	*/	
	function getColorAgendaByIdAgenda ($pIdAgenda){
		$agendaType = new AgendaType;
		
		$typeAgenda = $this->getTypeAgendaByIdAgenda($pIdAgenda);
		$arColor    = $agendaType->getColors($typeAgenda);
		
		return $arColor;
	}
	
	
	/**
	* Fonction qui retourne un tableau associant l'identifiant de l'�v�nement et sa couleur d'affichage
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/08
	* @param array $pArEvent le tableau d'�v�nements
	* @return array $arColorEvent tableau associant la couleur d'affichage � un �v�nement
	*/	
	function getColorByIdEvent ($pArEvent){
		$arColorEvent = array();
		foreach((array)$pArEvent as $event){
			$arColorEvent[$event->id_event] = $this->getColorAgendaByIdAgenda($event->id_agenda);
		}
		return $arColorEvent;
	}
	
	
	/**
	* Fonction qui retourne un tableau associant l'identifiant de l'�v�nement et sa couleur d'affichage
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/08
	* @param array $pArIdAgenda tableau d'identifiant d'agenda
	* @return array $arTitleAgenda tableau associant l'identifiant de l'agenda � son titre
	*/	
	function getTilteAgendaByIdAgenda ($pArIdAgenda){
				
		foreach($pArIdAgenda as $key=>$idAgenda){
			$daoSearchParams = _daoSp ();
			$daoSearchParams->addCondition ('id_agenda', '=', $idAgenda);	
			$daoAgenda = & CopixDAOFactory::getInstanceOf ('agenda|agenda');
			$arAgenda  = $daoAgenda->findBy ($daoSearchParams);

			if(count($arAgenda)>0){
				$arTitleAgenda[$key] = $arAgenda[0]->title_agenda;
			}
		}
		return $arTitleAgenda;
	}

	/**
	* Renvoie le titre � afficher et le parent de l'agenda. Se base sur la session. Deux cas :
    1. On affiche 1 seul agenda : on va chercher le parent, notamment pour en d�duire le titre
    2. On affiche plusieurs agendas : le titre devient g�n�rique ("Agendas"), et il n'y a pas de parent direct
	* @author Christophe Beyer <cbeyer@cap-tic.fr>
	* @since 2006/11/23
	* @return array Tableau avec ['title'] (le titre) et ['parent'] (tout le parent, si on n'affiche un seul agenda)
	*/	
  function getCurrentTitle () {
    $res = array();
    $listAgendasAffiches = $this->getAgendaAffiches();
    if (count($listAgendasAffiches)==1) {
      $keys = array_keys($listAgendasAffiches);
      $parent = Kernel::menuReturntoParent( "MOD_AGENDA", $keys[0]);
      $res['title'] = $parent['node_name'];
			$txt = (isset($parent['txt'])) ? $parent['txt'] : '';
			$url = (isset($parent['url'])) ? $parent['url'] : '';
      $res['parent'] = array('txt'=>$txt, 'url'=>$url);
    } else {
      $res['title'] = CopixI18N::get ('agenda|agenda.titlePage.agendas');
    }
    return $res;
  }


  
	/**
	 * Renvoie le menu des agendas
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2010/10/20
	 * @param string $iCurrent Onglet a allumer
	 * @return array Tableau du menu a afficher
	 */  
  function getAgendaMenu ($iCurrent) {
    $menu = array();
    
    // Affichage hebdomadaire
		$menu_txt = CopixI18N::get('agenda.menu.back');
		$menu_type = 'week';
		$menu_url = CopixUrl::get ('agenda|agenda|vueSemaine');
		$menu[] = array('txt'=>$menu_txt,'type' => $menu_type, 'current' => ($iCurrent==$menu_type), 'url' => $menu_url);
    
    // Liste des agendas (popup)
		$menu_txt = CopixI18N::get ('agenda|agenda.menu.agendalist');
		$menu_type = 'agendalist';
		$menu_behavior = 'fancybox';
		$menu_url = CopixUrl::get ('agenda|agenda|agendaList');
		$menu[] = array('txt'=>$menu_txt,'type' => $menu_type, 'current' => ($iCurrent==$menu_type), 'behavior' => $menu_behavior, 'url' => $menu_url);
    
    // Nouvel evenement
    $listAgendasAffiches = AgendaService::getAgendaAffiches();
    $ableToWrite = false;
    $ableToModerate = false;
		//on v�rifie les droits des utilisateurs sur la liste des agendas affich�s
		foreach((array)$listAgendasAffiches as $id_agenda){
			//on v�rifie si l'utilisateur a les droits d'�criture sur un des agendas affich�
			if(AgendaAuth::getCapability($id_agenda) >= AgendaAuth::getWriteAgenda()){
				$ableToWrite = true;
			}
			if(AgendaAuth::getCapability($id_agenda) >= AgendaAuth::getModerate()){
				$ableToModerate = true;
			}
		}		
		if($ableToWrite) {
  		$menu_txt = CopixI18N::get('agenda.menu.ajoutEvent');
      $menu_type = 'create';
  		$menu_url = CopixUrl::get ('agenda|event|create');
  		$menu[] = array('txt'=>$menu_txt,'type' => $menu_type, 'current' => ($iCurrent==$menu_type), 'url' => $menu_url);
		}
		if($ableToModerate) {
  		$menu_txt = CopixI18N::get('agenda.menu.import');
      $menu_type = 'import';
  		$menu_url = CopixUrl::get ('agenda|importexport|prepareImport');
  		$menu[] = array('txt'=>$menu_txt,'type' => $menu_type, 'current' => ($iCurrent==$menu_type), 'url' => $menu_url);
		}
    
    // Export
		$menu_txt = CopixI18N::get('agenda.menu.export');
		$menu_type = 'export';
		$menu_url = CopixUrl::get ('agenda|importexport|prepareExport');
		$menu[] = array('txt'=>$menu_txt,'type' => $menu_type, 'current' => ($iCurrent==$menu_type), 'url' => $menu_url);
    
     
    return $menu;
  
  }

}
?>
