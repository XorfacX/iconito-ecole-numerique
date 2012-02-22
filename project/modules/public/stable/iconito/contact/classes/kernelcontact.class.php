<?php

/**
 * Fonctions relatives au kernel et au module Contact
 * 
 * @package Iconito
 * @subpackage	Forum
 */
class KernelContact {

	/**
	 * Cr�ation d'un module de contacts
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2010/08/25
   * @param array $infos (option) Infos sur le module. [title], [subtitle], [node_type], [node_id]
	 * @return integer l'Id du module cree ou NULL si erreur
	 */
	function create ($infos=array()) {
		$record = _record('contact|contacts');
		$newForum->titre = (isset($infos['title']) && $infos['title']) ? $infos['title'] : '';
		$newForum->date_creation = date("Y-m-d H:i:s");
		_dao('contact|contacts')->insert ($record);
		return ($record->id!==NULL) ? $record->id : NULL;
	}

	/**
	 * Suppression d'un module de contacts
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2010/08/25
	 * @param integer $id Id du module a supprimer
	 * @return boolean true si la suppression s'est bien pass�e, false sinon
	 */
	function delete ($idContacts) {
		$res = false;
		if ($record = _dao('contact|contacts')->get($idContacts)) {
			_dao('contact|contacts')->delete ($idContacts);
			$res = true;
		}
    Kernel::unregisterModule("MOD_CONTACT", $idContacts);
		return $res;
	}

	/**
	 * Statistiques d'un module de contacts
	 *
	 * Renvoie des �l�ments chiffr�s relatifs � un module de contacts : nombre de messages...
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2010/08/25
	 * @param integer Id du module a analyser
	 * @return array Tableau dont les clefs repr�sentent les libell�s des stats et les valeurs les stats chiffr�es.
	 */
	function getStats ($idContacts) {
		$res = array();	
		return $res;
	}


	/**
	 * Statistiques d'un module de contacts
	 *
	 * Renvoie des �l�ments chiffr�s relatifs � un module de contacts : nombre de messages...
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2010/08/25
	 * @return array Tableau dont les clefs repr�sentent les libell�s des stats et les valeurs les stats chiffr�es.
	 */
	function getStatsRoot () {
		$res = array();
		return $res;
	}


}

