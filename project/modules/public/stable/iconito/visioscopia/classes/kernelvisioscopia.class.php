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
		/*
		$return = NULL;
		$dao = _dao("malle|malle_malles");
		$new = _daoRecord("malle|malle_malles");
		$new->titre = ($infos['title']) ? $infos['title'] : CopixI18N::get ('malle|malle.moduleDescription');
		$new->date_creation = date("Y-m-d H:i:s");
		$new->cle = MalleService::createKey();
		$dao->insert ($new);
		if ($new->id!==NULL) {
			$path2data = realpath('./static/malle');
			$folder = $path2data.'/'.$new->id.'_'.$new->cle;
			if ($mkdir = @mkdir ($folder, 0777)) {
				chmod ($folder, 0777);
				$return = $new->id;
			}
		}
		return $return;
		*/
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
		/*
		$daoMalles = _dao('malle|malle_malles');
		$dbw = & CopixDbFactory::getDbWidget ();
		$rMalle = $daoMalles->get($id);
		$res = false;
		if ($rMalle) {
			// On vide le r�pertoire
			$path2data = realpath('./static/malle');
			$folder = $path2data.'/'.$rMalle->id.'_'.$rMalle->cle;
		  if ($dh = opendir($folder)) {
				while (($obj = readdir($dh))) {
					if($obj=='.' || $obj=='..') continue;
		      unlink($folder.'/'.$obj);
				}
			}
			// On efface le r�pertoire
			$rmdir = rmdir ($folder);
			$dbw->doDelete ('module_malle_files', array('malle'=>$id));
			$dbw->doDelete ('module_malle_folders', array('malle'=>$id));
			$daoMalles->delete ($id);
			$res = true;
		}
		return $res;
		*/
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
		/*
		$daoMalles = _dao("malle|malle_malles");
		$res = array();	
		$infosFiles = $daoMalles->getNbsFilesInMalle($malle);
		$infosFolders = $daoMalles->getNbsFoldersInMalle($malle);
		$res['nbFiles'] = array ('name'=>CopixI18N::get ('malle|malle.stats.nbFiles', array($infosFiles[0]->nbFiles)));
		$res['nbFolders'] = array ('name'=>CopixI18N::get ('malle|malle.stats.nbFolders', array($infosFolders[0]->nbFolders)));
		$res['size'] = array ('name'=>CopixI18N::get ('malle|malle.stats.size', array(KernelMalle::human_file_size($infosFiles[0]->taille))));
		return $res;
		*/
	}
	
	/**
	 * Statistiques du module documents
	 *
	 * Renvoie des �l�ments chiffr�s relatifs aux documents et d�di�s � un utilisateur syst�me : taille occup�e (format "humain"), nombre de zones de documents, nombre de dossiers, nombre de fichiers
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/03/20
	 * @return array Tableau dont les clefs repr�sentent les libell�s des stats et les valeurs les stats chiffr�es. Clefs utilis�es : ["nbMalles"] ["nbFolders"] ["nbFiles"] ["size"]
	 */
	function getStatsRoot () {
		/*
		$res = array();	
		$dbw = & CopixDbFactory::getDbWidget ();
		$sql = 'SELECT COUNT(id) AS nb FROM module_malle_malles';
		$a = $dbw->fetchFirst ($sql);
		$res['nbMalles'] = array ('name'=>CopixI18N::get ('malle|malle.stats.nbMalles', array($a->nb)));
		$sql = 'SELECT COUNT(id) AS nb FROM module_malle_folders';
		$a = $dbw->fetchFirst ($sql);
		$res['nbFolders'] = array ('name'=>CopixI18N::get ('malle|malle.stats.nbFolders', array($a->nb)));
		$sql = 'SELECT COUNT(id) AS nb FROM module_malle_files';
		$a = $dbw->fetchFirst ($sql);
		$res['nbFiles'] = array ('name'=>CopixI18N::get ('malle|malle.stats.nbFiles', array($a->nb)));
		$sql = 'SELECT SUM(taille) AS nb FROM module_malle_files';
		$a = $dbw->fetchFirst ($sql);
		$res['size'] = array ('name'=>CopixI18N::get ('malle|malle.stats.size', array(KernelMalle::human_file_size($a->nb))));
		return $res;
		*/
	}

	
}

?>