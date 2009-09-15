<?php

/**
 * Fonctions relatives au kernel et au module Carnet
 * 
 * @package Iconito
 * @subpackage Carnet
 */
class KernelCarnet {


	/**
	 * Statistiques du module carnet
	 *
	 * Renvoie des �l�ments chiffr�s relatifs aux carnets de correspondance et d�di�s � un utilisateur syst�me : nombre de messages...
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/03/20
	 * @return array Tableau dont les clefs repr�sentent les libell�s des stats et les valeurs les stats chiffr�es. Clefs utilis�es : ["nbTopics"] ["nbMessages"]
	 */
	function getStatsRoot () {
		$res = array();	
		$dbw = & CopixDbFactory::getDbWidget ();
		$sql = 'SELECT COUNT(id) AS nb FROM module_carnet_topics';
		$a = $dbw->fetchFirst ($sql);
		$res['nbTopics'] = array ('name'=>CopixI18N::get ('carnet|carnet.stats.nbTopics', array($a->nb)));
		$sql = 'SELECT COUNT(id) AS nb FROM module_carnet_messages';
		$a = $dbw->fetchFirst ($sql);
		$res['nbMessages'] = array ('name'=>CopixI18N::get ('carnet|carnet.stats.nbMessages', array($a->nb)));
		return $res;
	}


}

