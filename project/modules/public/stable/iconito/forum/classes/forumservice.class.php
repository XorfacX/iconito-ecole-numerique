<?php

/**
 * Fonctions diverses du module Forum
 * 
 * @package Iconito
 * @subpackage	Forum
 */
class ForumService {

	/**
	 * Ajoute un message dans une discussion d'un forum
	 *
	 * Ajoute un message dans le forum et ex�cute les actions li�es (mise � jour du nombre de messages de la discussions, alertes...)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/08
	 * @param integer $topic Id de la discussion
	 * @param integer $forum Id du forum
	 * @param integer $auteur Id de l'utilisateur auteur du message
	 * @param string $message Corps du message � ins�rer
	 * @param string $format Format message � ins�rer
	 * @return integer l'Id du message ins�r� ou NULL si erreur
	 */
	function addForumMessage ($topic, $forum, $auteur, $message, $format) {
	
		$res = NULL;
		
		if (1) {
			
			$daoMessages = CopixDAOFactory::create("forum_messages_topics");
			
			$newMessage = CopixDAOFactory::createRecord("forum_messages_topics");
			$newMessage->topic = $topic;
			$newMessage->forum = $forum;
			$newMessage->auteur = $auteur;
			$newMessage->message = $message;
			$newMessage->format = $format;
			$newMessage->date = date("Y-m-d H:i:s");
			$newMessage->status = 1;
			$newMessage->nb_alertes = 0;
			$daoMessages->insert ($newMessage);
			
			if ($newMessage->id!==NULL) {
				$res = $newMessage->id;
			}
			ForumService::updateInfosTopics ($topic);
		}
		return $res;
	}
	

	/**
	 * Ajoute une discussion (avec le premier message)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/09
	 * @param integer $forum Id du forum
	 * @param integer $auteur Id de l'utilisateur auteur de la discussion
	 * @param string $message Corps du premier message de la discussion
	 * @param string $format Format du premier message de la discussion
	 * @return integer l'Id de la discussion d�marr�e ou NULL si erreur
	 */
	function addForumTopic ($forum, $auteur, $titre, $message, $format) {
	
		$res = NULL;
		
		if (1) {
			
			$daoTopics = CopixDAOFactory::create("forum_topics");
			
			$newTopic = CopixDAOFactory::createRecord("forum_topics");
			$newTopic->titre = $titre;
			$newTopic->forum = $forum;
			$newTopic->createur = $auteur;
			$newTopic->nb_messages = 0;
			$newTopic->nb_lectures = 0;
			$newTopic->status = 1;
			$newTopic->date_creation = date("Y-m-d H:i:s");
			$daoTopics->insert ($newTopic);
			
			if ($newTopic->id!==NULL) {
				$idMessage = ForumService::addForumMessage ($newTopic->id, $forum, $auteur, $message, $format);
				if ($idMessage!==NULL) {
					ForumService::updateInfosTopics ($newTopic->id);
					$res = $newTopic->id;
				} else {	// Prob d'insertion du message
				}
			}

		}
		return $res;
	}
	

	/**
	 * R�cup�re les infos d'une discussion et les met � jour (nb de messages, dernier message...)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/09
	 * @param integer $id_topic Id de la discussion concern�e
	 */
	function updateInfosTopics ($id_topic) {
		
		$dao_topics = CopixDAOFactory::create("forum_topics");
		$topic = $dao_topics->get($id_topic);
		if ($topic) {
			// 1. Le nb de messages
			$dao_messages = CopixDAOFactory::create("forum_messages_topics");
			$messages = $dao_messages->getListMessagesInTopicAll($id_topic);
			$nb_messages = count($messages);
			
			// 2. Le dernier message			
			if ($messages) {
				$last = $nb_messages-1;
				$last_msg_id = $messages[$last]->id;
				$last_msg_auteur = $messages[$last]->auteur;
				$last_msg_date = $messages[$last]->date;
			} else {
				$last_msg_id = $last_msg_auteur = $last_msg_date = NULL;
			}

			// 3. Mise � jour effective
			$topic->nb_messages = $nb_messages;
			$topic->last_msg_id = $last_msg_id;
			$topic->last_msg_auteur = $last_msg_auteur;
			$topic->last_msg_date = $last_msg_date;
			$dao_topics->update($topic);
		}
	}
	
	
	/**
	 * Enregistre la date de passage d'un utilisateur dans une discussion
	 *
	 * Cette fonction de "tracking" permet ensuite d'afficher, pour un utilisateur, les discussions dans lesquelles de nouveaux messages ont �t� �crits depuis sa derni�re lecture, et de le renvoyer au premier message non lu.
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/10
	 * @param integer $id_topic Id de la discussion
	 * @param integer $user Id de l'utilisateur
	 */
	function userReadTopic ($id_topic, $user) {
		$daoTracking = CopixDAOFactory::create("forum_tracking");
		$visite = $daoTracking->get($id_topic, $user);
		//print_r($visite);
		if ($visite) {	// Il a d�j� visit� ce topic
			$visite->last_visite = date("Y-m-d H:i:s");
			$daoTracking->update($visite);
		} else {	// 1e visite !
			$newVisite = CopixDAOFactory::createRecord("forum_tracking");
			$newVisite->topic = $id_topic;
			$newVisite->utilisateur = $user;
			$newVisite->last_visite = date("Y-m-d H:i:s");
			$daoTracking->insert ($newVisite);
		}
	}
	
	/**
	 * Suppression d'une discussion
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/09
	 * @param integer $id_topic Id de la discussion
	 * @return bool true si la suppression s'est bien pass�e, false sinon
	 */
	function deleteForumTopic ($id_topic) {
	
		$daoMessages = CopixDAOFactory::create("forum_messages_topics");
		$daoTopics = CopixDAOFactory::create("forum_topics");
		
		$res = false;
		$rTopic = $daoTopics->get($id_topic);
		if ($rTopic && $rTopic->status==1) {
			$messages = $daoMessages->getListMessagesInTopicAll($id_topic);
			foreach ($messages as $rMessage) {
				$rMessage->status=2;
				$daoMessages->update ($rMessage);
			}
			$rTopic->status=2;
			$daoTopics->update($rTopic);
			$res = true;
		}
		return $res;
	}	

	/**
	 * Gestion des droits dans un forum
	 *
	 * Teste si l'usager peut effectuer une certaine op�ration par rapport � son droit. Le droit sur le forum n�cessite d'�tre connu, renvoy� par le kernel avant l'entr�e dans cette fonction.
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/02
	 * @param string action Action pour laquelle on veut tester le droit
	 * @param integer droit Le droit de l'usager
	 * @return bool true s'il a le droit d'effectuer l'action, false sinon
	 */
	function canMakeInForum ($action, $droit) {
		$can = false;
		switch ($action) {
			case "READ" :
				$can = ($droit >= PROFILE_CCV_READ);
				break;
				
			case "ADD_TOPIC" :
			case "ADD_MESSAGE" :
				$can = ($droit >= PROFILE_CCV_MEMBER);
				break;	

			case "MODIFY_TOPIC" :
			case "DELETE_TOPIC" :
			case "MODIFY_MESSAGE" :
			case "DELETE_MESSAGE" :
				$can = ($droit >= PROFILE_CCV_MODERATE);
				break;	

		}
		return $can;
	}

}


?>