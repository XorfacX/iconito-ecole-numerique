<?php

/**
 * Surcharge de la DAO forum_topics
 * 
 * @package Iconito
 * @subpackage	Forum
 */
class DAOForum_Topics {

	/**
	 * La liste des discussion d'un forum, avec �ventuellement la date de derni�re lecture par l'utilisateur concern�
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/08
	 * @param integer $forum Id du forum
	 * @param integer $offset Position dans la liste des discussions (requ�te: ...LIMIT offset,count)
	 * @param integer $count Nombre de discussions � renvoyer (requ�te: ...LIMIT offset,count)
	 * @param string $orderby Champ ORDER BY de la requ�te (doit valoir last_msg_date ou date_creation)
	 * @param integer $user Id de l'utilisateur lisant le forum
	 * @return mixed Objet DAO
	 */
	function getListTopicsInForum ($forum, $offset, $count, $orderby, $user) {
		$critere = 'SELECT TOP.*, TRA.last_visite FROM module_forum_topics TOP LEFT JOIN module_forum_tracking TRA ON (TRA.topic=TOP.id AND TRA.utilisateur='.$user.') WHERE TOP.status=1 AND TOP.forum='.$forum.' ORDER BY '.$orderby.' DESC LIMIT '.$offset.', '.$count;
		return _doQuery($critere);
	}
			

}


?>
