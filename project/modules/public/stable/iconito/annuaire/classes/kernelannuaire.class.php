<?php

/**
 * Fonctions relatives au kernel et au module Annuaire
 * 
 * @package Iconito
 * @subpackage Annuaire
 */
class KernelAnnuaire {


	/**
	 * Statistiques du module annuaire
	 *
	 * Renvoie des �l�ments chiffr�s relatifs � l'annuaire et d�di�s � un utilisateur syst�me : nombre de messages...
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/03/20
	 * @return array Tableau dont les clefs repr�sentent les libell�s des stats et les valeurs les stats chiffr�es. Clefs utilis�es : ["nbEcoles"] ["nbClasses"] ["nbEleves"] ["nbPersonnel"] ["nbParents"] ["nbUsers"]
	 */
	function getStatsRoot () {
		$res = array();	
		$dbw = & CopixDbFactory::getDbWidget ();
		$sql = 'SELECT COUNT(numero) AS nb FROM kernel_bu_ecole';
		$a = $dbw->fetchFirst ($sql);
		$res['nbEcoles'] = array ('name'=>CopixI18N::get ('annuaire|annuaire.stats.nbEcoles', array($a->nb)));
		$sql = 'SELECT COUNT(id) AS nb FROM kernel_bu_ecole_classe';
		$a = $dbw->fetchFirst ($sql);
		$res['nbClasses'] = array ('name'=>CopixI18N::get ('annuaire|annuaire.stats.nbClasses', array($a->nb)));
		$sql = 'SELECT COUNT(numero) AS nb FROM kernel_bu_eleve';
		$a = $dbw->fetchFirst ($sql);
		$res['nbEleves'] = array ('name'=>CopixI18N::get ('annuaire|annuaire.stats.nbEleves', array($a->nb)));
		$sql = 'SELECT COUNT(numero) AS nb FROM kernel_bu_personnel';
		$a = $dbw->fetchFirst ($sql);
		$res['nbPersonnel'] = array ('name'=>CopixI18N::get ('annuaire|annuaire.stats.nbPersonnel', array($a->nb)));
		$sql = 'SELECT COUNT(numero) AS nb FROM kernel_bu_responsable';
		$a = $dbw->fetchFirst ($sql);
		$res['nbParents'] = array ('name'=>CopixI18N::get ('annuaire|annuaire.stats.nbParents', array($a->nb)));
		$sql = 'SELECT COUNT(id_cusr) AS nb FROM copixuser';
		$a = $dbw->fetchFirst ($sql);
		$res['nbUsers'] = array ('name'=>CopixI18N::get ('annuaire|annuaire.stats.nbUsers', array($a->nb)));
		return $res;
	}


}

