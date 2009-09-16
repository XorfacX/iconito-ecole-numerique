<?php

/**
 * Fonctions relatives au kernel et au module Agenda
 * 
 * @package Iconito
 * @subpackage Agenda
 */

require_once (COPIX_MODULE_PATH.'agenda/'.COPIX_CLASSES_DIR.'agendatype.class.php');

class KernelAgenda {

	/**
	 * Cr�ation d'un agenda
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/08/24
	 * @param array $infos (option) informations permettant d'initialiser le blog. Index: title, node_type, node_id
	 * @return integer l'Id de l'agenda cr�� ou NULL si erreur
	 */
	function create ($infos=array()) {
		
		$daoAgenda = &CopixDAOFactory::getInstanceOf ('agenda|agenda');
		
		$res = null;
		
		$agenda = & _daoRecord ('agenda|agenda');	
		if ($infos['title'])
			$agenda->title_agenda = $infos['title'];
		else
			//$agenda->title_agenda = CopixI18N::get ('agenda|agenda.default.title');
			$agenda->title_agenda = "Agenda";
		$agenda->desc_agenda = $agenda->title_agenda;
		$agenda->type_agenda = AgendaType::getAgendaTypeForNode ($infos['node_type'], $infos['node_id']);
		
		$daoAgenda->insert($agenda);

		return ($agenda->id_agenda!==NULL) ? $agenda->id_agenda : NULL;
	}

	/**
	 * Suppression d'un agenda
	 *
	 * Supprime un agenda, ses �v�nements, le�ons etc.
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/08/24
	 * @param integer $id Id de l'agenda � supprimer 
	 * @return boolean true si la suppression s'est bien pass�e, false sinon
	 */
	function delete ($id_agenda) {

	}

	/**
	 * Statistiques d'un agenda
	 *
	 * Renvoie des �l�ments chiffr�s relatifs � un agenda : nombre d'�v�nements...
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/08/24
	 * @param integer $id_agenda Id de l'agenda
	 * @return array Tableau dont les clefs repr�sentent les libell�s des stats et les valeurs les stats chiffr�es. Clefs utilis�es : ["nbEvenements"]
	 */
	function getStats ($id_agenda) {
		$res = array();	
		$dao = _dao("agenda|agenda");
		$infos = $dao->getNbsEvenementsInAgenda($id_agenda);
		$res['nbEvenements'] = array ('name'=>CopixI18N::get ('agenda|agenda.stats.nbEvenements', array($infos[0]->nbEvenements)));
		return $res;

	}
	
	/**
	 * Statistiques du module agenda
	 *
	 * Renvoie des �l�ments chiffr�s relatifs aux agendas et d�di�s � un utilisateur syst�me : nombre d'�v�nements...
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/03/20
	 * @return array Tableau dont les clefs repr�sentent les libell�s des stats et les valeurs les stats chiffr�es. Clefs utilis�es : ["nbAgendas"] ["nbEvenements"] ["nbLecons"]
	 */
	function getStatsRoot () {
		$res = array();	
		$sql = 'SELECT COUNT(A.id_agenda) AS nb FROM module_agenda_agenda A';
		$a = _doQuery($sql);
		$res['nbAgendas'] = array ('name'=>CopixI18N::get ('agenda|agenda.stats.nbAgendas', array($a[0]->nb)));
		$sql = 'SELECT COUNT(E.id_event) AS nb FROM module_agenda_event E';
		$a = _doQuery($sql);
		$res['nbEvenements'] = array ('name'=>CopixI18N::get ('agenda|agenda.stats.nbEvenements', array($a[0]->nb)));
		$sql = 'SELECT COUNT(L.id_lecon) AS nb FROM module_agenda_lecon L';
		$a = _doQuery($sql);
		$res['nbLecons'] = array ('name'=>CopixI18N::get ('agenda|agenda.stats.nbLecons', array($a[0]->nb)));
		return $res;
	}

}

