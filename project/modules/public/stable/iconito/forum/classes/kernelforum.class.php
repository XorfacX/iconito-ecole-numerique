<?php

/**
 * Fonctions relatives au kernel et au module Forum
 * 
 * @package Iconito
 * @subpackage	Forum
 */
class KernelForum {

	/**
	 * Cr�ation d'un forum
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/08
   * @param array $infos (option) Infos sur le module. [title], [subtitle], [node_type], [node_id]
	 * @return integer l'Id du forum cr�� ou NULL si erreur
	 */
	function create ($infos=array()) {
		$daoForum = _dao("forum|forum_forums");
		$newForum = _daoRecord("forum|forum_forums");
		$newForum->titre = (isset($infos['title']) && $infos['title']) ? $infos['title'] : '';
		$newForum->date_creation = date("Y-m-d H:i:s");
		$daoForum->insert ($newForum);
		return ($newForum->id!==NULL) ? $newForum->id : NULL;
	}

	/**
	 * Suppression d'un forum
	 *
	 * Supprime un forum, ses discussions etc.
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/09
	 * @param integer $id Id du forum � supprimer 
	 * @return boolean true si la suppression s'est bien pass�e, false sinon
	 */
	function delete ($idForum) {
		$daoForums = _dao("forum|forum_forums");
	 	$daoTopics = _dao("forum|forum_topics");
		$dbw = & CopixDbFactory::getDbWidget ();
		$rForum = $daoForums->get($idForum);
		$res = false;
		if ($rForum) {
			$criteres = _daoSearchConditions();
			$criteres->addCondition('forum', '=', $idForum);
			$topics = $daoTopics->findBy($criteres);
			while (list(,$topic) = each($topics)) {
				$dbw->doDelete ('module_forum_tracking', array('topic'=>$topic->id));
				$dbw->doDelete ('module_forum_messages', array('topic'=>$topic->id));
				$dbw->doDelete ('module_forum_topics', array('id'=>$topic->id));
			}
			$daoForums->delete ($idForum);
			$res = true;
		}
		return $res;
	}

	/**
	 * Statistiques d'un forum
	 *
	 * Renvoie des �l�ments chiffr�s relatifs � un forum : nombre de discussions, nombre de messages...
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/10
	 * @param integer $id_forum Id du forum � analyser
	 * @return array Tableau dont les clefs repr�sentent les libell�s des stats et les valeurs les stats chiffr�es. Clefs utilis�es : ["Discussions"] ["Messages"]
	 */
	function getStats ($id_forum) {
		$daoForum = _dao("forum|forum_forums");
		$res = array();	
		$infos = $daoForum->getNbTopicsInForum($id_forum);
		$res['nbTopics'] = array ('name'=>CopixI18N::get ('forum|forum.stats.nbTopics', array($infos[0]->nb)));
		$infos = $daoForum->getNbMessagesInForum($id_forum);
		$res['nbMessages'] = array ('name'=>CopixI18N::get ('forum|forum.stats.nbMessages', array($infos[0]->nb)));
		return $res;
	}


	/**
	 * Statistiques du module forum
	 *
	 * Renvoie des �l�ments chiffr�s relatifs aux forums et d�di�s � un utilisateur syst�me : nombre de discussions, nombre de messages...
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/03/19
	 * @return array Tableau dont les clefs repr�sentent les libell�s des stats et les valeurs les stats chiffr�es. Clefs utilis�es : ["nbForums"] ["nbTopics"] ["nbMessages"]
	 */
	function getStatsRoot () {
		$res = array();	
		$dbw = & CopixDbFactory::getDbWidget ();
		$sql = 'SELECT COUNT(F.id) AS nb FROM module_forum_forums F';
		$a = $dbw->fetchFirst ($sql);
		$res['nbForums'] = array ('name'=>CopixI18N::get ('forum|forum.stats.nbForums', array($a->nb)));
		$sql = 'SELECT COUNT(T.id) AS nb FROM module_forum_topics T';
		$a = $dbw->fetchFirst ($sql);
		$res['nbTopics'] = array ('name'=>CopixI18N::get ('forum|forum.stats.nbTopics', array($a->nb)));
		$sql = 'SELECT COUNT(M.id) AS nb FROM module_forum_messages M';
		$a = $dbw->fetchFirst ($sql);
		$res['nbMessages'] = array ('name'=>CopixI18N::get ('forum|forum.stats.nbMessages', array($a->nb)));
		return $res;
	}


}
