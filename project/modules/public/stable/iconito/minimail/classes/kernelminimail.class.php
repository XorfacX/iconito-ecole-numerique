<?php

/**
 * Fonctions relatives au kernel et au module Minimail
 * 
 * @package Iconito
 * @subpackage Minimail
 */
class KernelMinimail {



	/**
	 * Statistiques du module minimail
	 *
	 * Renvoie des �l�ments chiffr�s relatifs aux minimails et d�di�s � un utilisateur syst�me : nombre de messages...
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/03/20
	 * @return array Tableau dont les clefs repr�sentent les libell�s des stats et les valeurs les stats chiffr�es. Clefs utilis�es : ["nbMessages"] ["nbMessages24h"]
	 */
	function getStatsRoot () {
		$res = array();	
		$dbw = & CopixDbFactory::getDbWidget ();
		$sql = 'SELECT MAX(id) AS nb FROM module_minimail_from';
		$a = $dbw->fetchFirst ($sql);
		$res['nbMessages'] = array ('name'=>CopixI18N::get ('minimail|minimail.stats.nbMessages', array(1=>$a->nb)));
		$sql = 'SELECT COUNT(id) AS nb FROM module_minimail_from WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(date_send)<=60*60*24';
		$a = $dbw->fetchFirst ($sql);
		$res['nbMessages24h'] = array ('name'=>CopixI18N::get ('minimail|minimail.stats.nbMessages24h', array(1=>$a->nb)));
		return $res;
	}


}

