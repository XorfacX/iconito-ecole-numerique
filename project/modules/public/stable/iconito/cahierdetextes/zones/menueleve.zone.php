<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/ 
class ZoneMenuEleve extends CopixZone {

	function _createContent (& $toReturn) {
    
    _classInclude('agenda|dateservices');
    
	  $ppo = new CopixPPO ();                               
    
    // Récupération des paramètres    
    $ppo->cahierId = $this->getParam('cahierId');
    $ppo->jour     = $this->getParam('date_jour');
    $ppo->mois     = $this->getParam('date_mois');
    $ppo->annee    = $this->getParam('date_annee');
    $ppo->current  = $this->getParam('current');
    
    // Timestamp de la date sélectionnée
    $ppo->dateSelectionnee = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);
    $numeroSemaine         = date('W', $ppo->dateSelectionnee);
    
    $service  = new DateService;
    
    // Récupération des timestamp des jours de la semaine
		$ppo->lun = $service->numweekToDate($numeroSemaine, $ppo->annee, 1);
		$ppo->mar = $service->numweekToDate($numeroSemaine, $ppo->annee, 2);
		$ppo->mer = $service->numweekToDate($numeroSemaine, $ppo->annee, 3);
		$ppo->jeu = $service->numweekToDate($numeroSemaine, $ppo->annee, 4);
		$ppo->ven = $service->numweekToDate($numeroSemaine, $ppo->annee, 5);
		$ppo->sam = $service->numweekToDate($numeroSemaine, $ppo->annee, 6);

    $ppo->semainePrecedente = strtotime("-1 week", $ppo->lun);
    $ppo->semaineSuivante   = strtotime("+1 week", $ppo->lun);

    $toReturn = $this->_usePPO ($ppo, '_menu_eleve.tpl');
  }
}