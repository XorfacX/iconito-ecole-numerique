<?php
/**
 * Dans ce service, se trouvent toutes les op�rations sur les dates
 * @package Iconito
 * @subpackage Agenda
 * @author Audrey Vassal 
 * @copyright 2001-2005 CopixTeam
 * @link http://copix.org
 * @licence http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

require_once (COPIX_UTILS_PATH.'CopixDateTime.class.php');

class DateService {
	
	/**
	* Ajoute un nombre de jours/mois/ann�es � une date et retourne la nouvelle date obtenue.
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/07/24 
	* @param string $ToDate La date que l'on va incr�menter. Format Fr.
	* @param integer $Day le nombre de jours � ajouter.
	* @param integer $Month le nombre de mois a ajouter.
	* @param integer $year le nombre d'ann�es � ajouter.
	* @param string $SplitChar le caractere s�parateur utilis� dans les dates (par defaut : /)
	* @return string La date modifi�e. Format fr jj-mm-aaaa.
	*/
    function addToDate ($ToDate, $Day, $Month = 0, $Year = 0, $SplitChar = '/'){
			
				
        $TblToDate = explode ($SplitChar, $ToDate); //Tableau avec les valeurs actuelles.
        $NewValue = mktime (0, 0, 0, $TblToDate[1] + $Month, $TblToDate[0] + $Day, $TblToDate[2] + $Year);        
		return date('d' . $SplitChar . 'm' . $SplitChar . 'Y', $NewValue); //Reconversion de la valeur en format date.
    }
	
	
	/*
	* Fonction qui donne le nombre de jours �coul�s entre deux dates
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/16
	* @param date $pDate1 au format yyyymmdd
	* @param date $pDate1 au format yyyymmdd
	* @return integer $nbDay le nombre de jours �coul�s
	*/
	function getNombreJoursEcoulesEntreDeuxDates($pDate1, $pDate2){
		$date1 = $this->dateAndHoureBdToTimestamp($pDate1, null);
		$date2 = $this->dateAndHoureBdToTimestamp($pDate2, null);
		$nbSec = $date1 - $date2;//nb de sec entre les deux jours
		$nbDays = $nbSec/86400; //86400 est le nb de sec dans une journ�e
		return $nbDays;
	}
	
	
	/**
	* Soustrait un jour � une date
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/11
	* @param date $pDate date au format yyyymmdd
	* @return int la date moins 1 jour
	*/
    function retireUnJour ($pDate){
		$date = mktime(0 ,0, 0, substr($pDate, 4, 2), substr($pDate, 6, 2), substr($pDate, 0, 4));
		$date = $date - 60*60*24;
		$date = date('Ymd', $date);
		return $date;
    }

	
	/*
	* Fonction qui donne la date du jour de la semaine qui suit une date donn�e
	* Utilis�e pour la reprise apr�s une date($pDate), d'un �v�nement qui se r�p�te toutes les semaines
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/11
	* @param date $pDate date de r�f�rence au format yyyymmdd
	* @param integer $pJour le jour de la semaine auquel d�bute l'�v�nement (0 pour dimanche, 6 pour samedi)
	* @return date $date la date recherch�e au format yyyymmdd
	*/
	function getDayOfWeekAfterDate($pDate, $pJour){
		$nbJourAjout = 7 - date('w', $this->dateAndHoureBdToTimestamp($pDate, null)) + $pJour;
		$date = $this->addToDate($this->dateBddToDateFr($pDate), $nbJourAjout, 0, 0);
		return $this->dateFrToDateBdd($date);
	}
	
	
	/*
	* Fonction qui donne le jour du mois qui suit une date donn�e
	* Utilis�e pour la reprise apr�s une date($pDate), d'un �v�nement qui se r�p�te tous les mois
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/16
	* @param date $pDate date de r�f�rence au format yyyymmdd
	* @param integer $pJour le jour du mois auquel d�bute l'�v�nement
	* @return date $date la date recherch�e au format yyyymmdd
	*/
	function getDayOfMonthAfterDate($pDate, $pJour){
		
		$nbJourEcart = (substr($pDate, 6, 2) - $pJour);
		
		if ($nbJourEcart < 0){
			$date = $this->addToDate($this->dateBddToDateFr($pDate), -($nbJourEcart), 0, 0);
			$date = $this->dateFrToDateBdd($date);
		}
		else{
			$date = $this->addToDate($this->dateBddToDateFr($pDate), 0, 1, 0);
			$date = $this->dateFrToDateBdd($date);
			$date = mktime(0 ,0, 0, substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4));
			$date = $date - 60*60*24*$nbJourEcart;echo'<br>';
			$date = date('Ymd', $date);
		}
		return $date;
	}
	
	
	/*
	* Fonction qui le jour de l'ann�e qui suit une date donn�e
	* Utilis�e pour la reprise apr�s une date($pDate), d'un �v�nement qui se r�p�te tous les ans
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/16
	* @param date $pDate date de r�f�rence au format yyyymmdd
	* @param integer $pJour le jour de l'ann�e auquel d�bute l'�v�nement (format mmdd)
	* @return date $date la date recherch�e au format yyyymmdd
	*/
	function getDayOfYearAfterDate($pDate, $pJour){
		if(substr($pDate, 4, 4) < $pJour){//si l'�v�nement commence apr�s $pDate, on reste dans l'ann�e de $pDate
			$date = substr($pDate, 0, 4).$pJour;
		}
		else{
			$year = substr($pDate, 0, 4) + 1;
			$date = $year.$pJour;
		}
		return $date;
	}

	
	/**
	* Convertit une date (+ heure) en timestamp
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/07/27
	* @param integer $pDate La date a convertir (au format bdd : yyyymmdd)
	* @param string $pHour L'heure (au format : hh:mm)
	* @return string La date en timestamp
	*/
    function dateAndHoureBdToTimestamp ($pDate, $pHour) {
//      print_r("dateAndHoureBdToTimestamp ($pDate, $pHour)");
        if ($pHour) {
            $hour = substr ($pHour, 0, strpos($pHour, ':'));
            $minut = substr ($pHour, strpos($pHour, ':') + 1, 2);
        } 
        $day = substr($pDate, 6, 2);
        $month = substr($pDate, 4, 2);
        $year = substr($pDate, 0, 4);
		return mktime($hour, $minut, 0, $month, $day, $year);
	}
	
	
	/**
	* Fonction qui donne le nombre de jour �coul�s entre deux dates+heure
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/07/28
	* @param date $pDateBegin au format jj/mm/aaaa
	* @param date $pDateEnd au format jj/mm/aaaa
	* @param date $pHeureDeb heure au format hh:mm
	* @param date $pHeureFin heure au format hh:mm
	* @return integer $nbDays nombre de jours qui se sont �coul�s entre les deux dates
	*/
	function getNomberDaysBeetweenTwoDates($pDateBegin, $pDateEnd, $pHeureBegin, $pHeureEnd){
		//Extraction des donn�es
		//list($jour1, $mois1, $annee1) = explode('/', $pDateBegin); 
		//list($jour2, $mois2, $annee2) = explode('/', $pDateEnd);
		$pDate = CopixDateTime::dateToTimestamp ($pDateBegin);
		$jour1 = substr($pDate, 6, 2);
    $mois1 = substr($pDate, 4, 2);
    $annee1 = substr($pDate, 0, 4);
		$pDate = CopixDateTime::dateToTimestamp ($pDateEnd);
		$jour2 = substr($pDate, 6, 2);
    $mois2 = substr($pDate, 4, 2);
    $annee2 = substr($pDate, 0, 4);
		
		list($heure1, $minutes1) = explode (':', $pHeureBegin);
		list($heure2, $minutes2) = explode (':', $pHeureEnd);
		//Calcul des timestamp
		$timestamp1 = mktime($heure1, $minutes1, 0, $mois1, $jour1, $annee1); 
		$timestamp2 = mktime($heure2, $minutes2, 0, $mois2, $jour2, $annee2); 
		$nbDays = ($timestamp2 - $timestamp1)/86400;
		
		return $nbDays;
		//echo abs($timestamp2 - $timestamp1)/(86400*7); //Affichage du nombre de semaine : 3.85	
	}
	
	
	/**
	* Fonction qui convertit une date au format dd/mm/yyyy en format yyyymmdd
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/07/31
	* @param date $pDateToConvert au format jj/mm/aaaa
	* @return date au format yyyymmdd
	*/
	function dateFrToDateBdd($pDateToConvert){
		return substr($pDateToConvert, 6, 4) . substr($pDateToConvert, 3, 2) . substr($pDateToConvert, 0, 2);
	}
	
	
	/**
	* Fonction qui convertit une date au format yyyymmdd en format dd/mm/yyyy
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/07/31
	* @param date $pDateToConvert au format yyyymmdd
	* @return date au format dd/mm/yyyy
	*/
	function dateBddToDateFr($pDateToConvert){
		return substr($pDateToConvert, 6, 2) . '/' . substr($pDateToConvert, 4, 2) . '/' . substr($pDateToConvert, 0, 4);
	}
	
	
	/**
	* Fonction qui �limine l'�l�ment qui s�pare les heures et les minutes
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/07/31
	* @param heure $pHeureToConvert au format hh:mm
	* @return heure sans s�parateur (hhmm)
	*/
	function heureWithSeparateurToheureWithoutSeparateur($pHeureToConvert){
		return str_replace(':', '', $pHeureToConvert);
	}
	
	/**
	* Fonction qui �limine remet l'�l�ment qui s�pare les heures et les minutes
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/02
	* @param heure $pHeureToConvert au format hhmm
	* @return heure avec s�parateur (hh:mm)
	*/
	function heureWithoutSeparateurToheureWithSeparateur($pHeureToConvert){
		//cas o� on a une heure au format hhmm
		if(strlen($pHeureToConvert) == 4){
			return substr($pHeureToConvert, 0, 2) . ':' .substr($pHeureToConvert, 2, 2);
		}
		//cas o� on a une heure au format hmm
		else{
			return substr($pHeureToConvert, 0, 1) . ':' .substr($pHeureToConvert, 1, 2);
		}
	}
	

	/**
	* Fonction qui retourne le num�ro de semaine d'une date donn�e
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/07/31
	* @param date $date au format timestamp
	* @return int le num�ro de la semaine correspondant � la date
	*/
	function dateToWeeknum($date) {
		// cette fonction calcule � partir d'une date le num�ro de semaine associ�
		// "la premiere semaine de l'ann�e est celle dans laquelle se trouve le premier jeudi de l'ann�e"
		$tmp_date = mktime(0,0,0,01,01,date("Y",$date));
		
		// initialisation de la date de d�part du calcul
		// initialisation au premier lundi de l'ann�e
		// indique au passage si ce lundi appartient � la semaine 1 ou 2 de l'ann�e
		switch(date("w",$tmp_date)) {
			case 1:
				$tmp_date = mktime(0,0,0,01,01,date("Y",$date));
				$tmp_delta_week = 0;
			break;
			case 0:
				$tmp_date = mktime(0,0,0,01,02,date("Y",$date));
				$tmp_delta_week = 0;
			break;
			case 6:
				$tmp_date = mktime(0,0,0,01,03,date("Y",$date));
				$tmp_delta_week = 0;
			break;
			case 5:
				$tmp_date = mktime(0,0,0,01,04,date("Y",$date));
				$tmp_delta_week = 0;
			break;
			case 4:
				$tmp_date = mktime(0,0,0,01,05,date("Y",$date));
				$tmp_delta_week = 1;
			break;
			case 3:
				$tmp_date = mktime(0,0,0,01,06,date("Y",$date));
				$tmp_delta_week = 1;
			break;
			case 2:
				$tmp_date = mktime(0,0,0,01,07,date("Y",$date));
				$tmp_delta_week = 1;
			break;
		}
		
		if ($date >= $tmp_date) { 	// si la date recherch�e est post�rieure au premier lundi de l'ann�e
			
			// nombre de jours �coul�s depuis la date de d�but du calcul
			$tmp_nbjours = date("z",mktime(0,0,0,date("m",$date),date("d",$date),date("Y",$date)))-date("z",mktime(0,0,0,date("m",$tmp_date),date("d",$tmp_date),date("Y",$tmp_date)));
			
			// nombre de semaines �coul�es
			$tmp_numsem = floor($tmp_nbjours/7)+$tmp_delta_week+1;
			if ($tmp_numsem < 10){
				$tmp_numsem = "0".$tmp_numsem;
			} // mise en forme du nombre de semaines
			
			if ($tmp_numsem == 53) { 	// si on a trouv� la semainhe n�53 : attention au pi�ge : n'est-ce pas une semaine 1 anticip�e ?
				//echo( date("d-m-Y",mktime(0,0,0,date("m",$date),date("d",$date)+delta_to_thursdaysameweek($date),date("Y",$date)))." || ".date("d-m-Y",mktime(0,0,0,01,01,date("Y",$date)+1)));
				if ( date("Y",mktime(0,0,0,date("m",$date),date("d",$date)+$this->delta_to_thursdaysameweek($date),date("Y",$date))) == date("Y",mktime(0,0,0,01,01,date("Y",$date)+1)) ) {
					// si le jeudi de cette semaine tombe l'ann�e prochaine alors on est en semaine 1
					return "01";
				}
				else{
					// si le jeudi de cette semaine tombe cette ann�e alors on est en semaine 53
					return "53";
				}
			}
			else{// si on est en semaine 1 � 52, ok.
				return $tmp_numsem;
			}
		}
		else{ 	// si la date recherch�e est ant�rieure au premier lundi de l'ann�e
			if ($tmp_delta_week == 1){
				// si on avait not� une semaine de d�calage, les jours ant�rieurs au premier lundi sont tous en semaine 1
				return "01";
			}
			else{
				// si on n'avait pas not� de semaine de d�calage, les jours ant�rieurs au premier lundi sont tous de la meme semaine que la derni�re semaine de l'ann�e d'avant.
				return $this->dateToWeeknum(mktime(0,0,0,12,31,date("Y",$date)-1));
			}
		}
	}
	
	
	
	function delta_to_thursdaysameweek($date) {
		if (date("w",$date)==0) {
			return -3;
		} else {
			return 4-date("w",$date);
		}
	}
	
	/**
	* Fonction qui retourne la date du jour en fonction du num�ro de la semaine et de l'ann�e
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/07/31
	* @param int $numweek num�ro de la semaine
	* @param int $year l'ann�e
	* @param int $dayOfWeek le jour de la semaine demand� (0=>dimanche, 6=>samedi)
	* @return int $tmp_date la date du jour demand�
	*/
	function numweekToDate($numweek,$year,$dayOfWeek) {
		// cette fonction calcule � partir d'un num�ro de semaine, d'une ann�e et d'un jour de semaine la date associ�e.
		$tmp_date = mktime(0,0,0,01,01,$year);
		// si les param�tres sont mal format�s, la fonction renvoie false
		if (is_nan($numweek) || is_nan($year) || is_nan ($dayOfWeek) || $numweek > 53 || $numweek < 0 || $dayOfWeek < 0 || $dayOfWeek > 6 || $year <1970 || $year>2030) { 
			return false;
			exit;
		}
		// initialisation de la date de d�part du calcul
		// initialisation au premier lundi de l'ann�e
		// indique au passage si ce lundi appartient � la semaine 1 ou 2 de l'ann�e
		switch(date("w",$tmp_date)) {
			case 1:
				$tmp_date = mktime(0,0,0,01,01,$year);
				$tmp_delta_week = 0;
				break;
			case 0:
				$tmp_date = mktime(0,0,0,01,02,$year);
				$tmp_delta_week = 0;
				break;
			case 6:
				$tmp_date = mktime(0,0,0,01,03,$year);
				$tmp_delta_week = 0;
				break;
			case 5:
				$tmp_date = mktime(0,0,0,01,04,$year);
				$tmp_delta_week = 0;
				break;
			case 4:
				$tmp_date = mktime(0,0,0,01,05,$year);
				$tmp_delta_week = 1;
				break;
			case 3:
				$tmp_date = mktime(0,0,0,01,06,$year);
				$tmp_delta_week = 1;
				break;
			case 2:
				$tmp_date = mktime(0,0,0,01,07,$year);
				$tmp_delta_week = 1;
				break;
		}
		if ($dayOfWeek>=1 && $dayOfWeek<=6) {
			$tmp_delta_day = $dayOfWeek -1;
		}
		elseif ($dayOfWeek==0) {
			$tmp_delta_day = 6;
		}	
		$tmp_date = mktime(0,0,0,date("m",$tmp_date),date("d",$tmp_date)+($numweek-1-$tmp_delta_week)*7 + $tmp_delta_day,date("Y",$tmp_date));
		//$tmp_date = date("d m Y",$tmp_date);
		// en cas de semaine 53, on v�rifie que la semaine 53 existe en effet, sinon on renvoie false
		if ($numweek==53) {
			if ($this->numweekToDate(01,$year+1,$dayOfWeek)==$tmp_date) {
				//$tmp_date = false;
				$tmp_date = $this->numweekToDate(52,$year,$dayOfWeek);
			}
		}
		return $tmp_date;
	}
	
	
	/**
	* Fonction qui retourne le nom du mois en fran�ais � partir de son num�ro
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/07/31
	* @param int $mois num�ro du mois (de 1 � 12)
	* @return string le mois sous forme lit�rale
	*/
	function moisNumericToMoisLitteral($mois){
		if ($mois == 1)
			return CopixI18N::get('agenda|agenda.message.jan');
		elseif ($mois == 2)
			return CopixI18N::get('agenda|agenda.message.fev');
		elseif ($mois == 3)
			return CopixI18N::get('agenda|agenda.message.mars');
		elseif ($mois == 4)
			return CopixI18N::get('agenda|agenda.message.avr');
		elseif ($mois == 5)
			return CopixI18N::get('agenda|agenda.message.mai');
		elseif ($mois == 6)
			return CopixI18N::get('agenda|agenda.message.juin');
		elseif ($mois == 7)
			return CopixI18N::get('agenda|agenda.message.juil');
		elseif ($mois == 8)
			return CopixI18N::get('agenda|agenda.message.aout');
		elseif ($mois == 9)
			return CopixI18N::get('agenda|agenda.message.sept');
		elseif ($mois == 10)
			return CopixI18N::get('agenda|agenda.message.oct');
		elseif ($mois == 11)
			return CopixI18N::get('agenda|agenda.message.nov');
		elseif ($mois == 12)
			return CopixI18N::get('agenda|agenda.message.dec');
	}
	
	
	/**
	* Fonction qui retourne le jour en fran�ais � partir de la date
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/18
	* @param date $pDate date du jour au format yyymmdd
	* @return string le jour sous forme lit�rale en fran�ais
	*/
	function dayNumericToDayLitteral($pDate){
		$date = $this->dateAndHoureBdToTimestamp($pDate, null);
		$jour = date('w', $date);
		if ($jour == 0)
			return CopixI18N::get('agenda|agenda.message.dim');
		elseif ($jour == 1)
			return CopixI18N::get('agenda|agenda.message.lun');
		elseif ($jour == 2)
			return CopixI18N::get('agenda|agenda.message.mar');
		elseif ($jour == 3)
			return CopixI18N::get('agenda|agenda.message.mer');
		elseif ($jour == 4)
			return CopixI18N::get('agenda|agenda.message.jeu');
		elseif ($jour == 5)
			return CopixI18N::get('agenda|agenda.message.ven');
		elseif ($jour == 6)
			return CopixI18N::get('agenda|agenda.message.sam');
	}
	
	
	/**
	* Fonction qui convertit un nombre de minutes en heures au format (hh:mm)
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/03
	* @param int $pNbMinutes le nombre de minutes � convertir
	* @return string l'heure au format hh:mm
	*/
	function convertMinutesInHours($pNbMinutes){
			
		$heures = floor( $pNbMinutes / 60 );
		$minutes = $pNbMinutes % 60;
		if(strlen($minutes) == 1){
			$minutes = '0' . $minutes;
		}

		return($heures . ':' . $minutes);
	}
	
	/**
	* Fonction qui convertit une heure au format (hh:mm) en minutes
	* @author Audrey Vassal <avassal@sqli.com> 
	* @since 2006/08/03
	* @param hour $pHours l'heure � convertir
	* @return int le nombre de minutes
	*/
	function convertHoursInMinutes($pHours){
			
		$Tbl = explode (':', $pHours);
		
		return($Tbl[0]*60 + $Tbl[1]);
	}

	
} 

?>
