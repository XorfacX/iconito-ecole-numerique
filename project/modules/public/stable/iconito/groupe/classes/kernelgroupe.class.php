<?php

/**
 * Fonctions relatives au kernel et au module Groupe
 * 
 * @package Iconito
 * @subpackage	Groupe
 */
class KernelGroupe {


	/**
	 * Statistiques d'un groupe
	 *
	 * Renvoie des �l�ments chiffr�s relatifs � un groupe : nombre de membres
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/17
	 * @return array Tableau dont les clefs repr�sentent les libell�s des stats et les valeurs les stats chiffr�es. Clefs utilis�es : ["Membres"]
	 */
	function getStats ($id_groupe) {
	
		//$kernelService = & CopixClassesFactory::Create ('kernel|kernel');
		$groupeService = & CopixClassesFactory::Create ('groupe|groupeService');
		$res = array();	
		
    $members = $groupeService->getNbMembersInGroupe ($id_groupe);
		$res['nbMembers'] = array ('name'=>CopixI18N::get ('groupe|groupe.stats.nbMembers', array($members['inscrits'])));

		if ($members['waiting'])
			$res['nbMembersWaiting'] = array ('name'=>CopixI18N::get ('groupe|groupe.stats.nbMembersWaiting', array($members['waiting'])));
		
		return $res;
	}


	/**
	 * Statistiques du module groupes de travail
	 *
	 * Renvoie des �l�ments chiffr�s relatifs aux groupes de travail et d�di�s � un utilisateur syst�me : nombre de groupes, de modules...
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/03/20
	 * @return array Tableau dont les clefs repr�sentent les libell�s des stats et les valeurs les stats chiffr�es. Clefs utilis�es : ["nbMessages"] ["nbMessages24h"]
	 */
	function getStatsRoot () {
		$res = array();	
		$dbw = & CopixDbFactory::getDbWidget ();
		$sql = 'SELECT COUNT(id) AS nb FROM module_groupe_groupe';
		$a = $dbw->fetchFirst ($sql);
		$res['nbGroupes'] = array ('name'=>CopixI18N::get ('groupe|groupe.stats.nbGroupes', array($a->nb)));
		$sql = "SELECT COUNT(user_id) AS nb FROM kernel_link_user2node WHERE node_type='CLUB' AND droit>=".PROFILE_CCV_READ;
		$a = $dbw->fetchFirst ($sql);
		$res['nbMembers'] = array ('name'=>CopixI18N::get ('groupe|groupe.stats.nbMembers', array($a->nb)));
		return $res;
	}


}

?>