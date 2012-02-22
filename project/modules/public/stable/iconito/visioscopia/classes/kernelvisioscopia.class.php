<?php
/**
 * Fonctions relatives au kernel et au module VisioScopia
 * 
 * @package Iconito
 * @subpackage	VisioScopia
 */

class KernelVisioScopia {


	/**
	 * Cr�ation d'une malle
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/06
	 * @param array $infos (option) informations permettant d'initialiser la malle. Index: title, node_type, node_id
	 * @return integer l'Id de la malle cr��e ou NULL si erreur
	 */
	function create ($infos=array()) {
		$return = NULL;
		$dao = _dao("module_visioscopia");
		$new = _record("module_visioscopia");
		$new->date_creation = date("Y-m-d H:i:s");
		$dao->insert ($new);
		return $new->id;
		
	}

	/**
	 * Suppression d'une malle
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/09
	 * @param integer $id Id de la malle
	 * @return boolean true si la suppression s'est bien pass�e, false sinon
	 */
	function delete ($id) {
		$dao = _dao("module_visioscopia");
		$dao->delete($id);
		
		return(true);
	}

	/**
	 * Statistiques d'une malle
	 *
	 * Renvoie des �l�ments chiffr�s relatifs � une malle : taille occup�e (format "humain"), nombre de dossiers, nombre de fichiers
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/07
	 * @param integer $malle Id de la malle
	 * @return array Tableau dont les clefs repr�sentent les libell�s des stats et les valeurs les stats chiffr�es. Clefs utilis�es : ["nbFiles"] ["nbFolders"] ["size"]
	 */
	function getStats ($malle) {
		
		
	}
	


	
}

?>
