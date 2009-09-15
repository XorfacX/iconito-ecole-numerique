<?php

/**
 * Objet Minimail
 * 
 * @package Iconito
 * @subpackage	Minimail
 */
class MinimailObject {

	/**
	 * Id du minimail
	 * @var integer
	 */
	var $id;
	/**
	 * Id utilisateur de l'exp�diteur
	 * @var integer 
	 */
	var $from_id;
	/**
	 * Login de l'exp�diteur
	 * @var string 
	 */
	var $from_login;
	/**
	 * Date d'envoi
	 * @var string 
	 */
	var $date_send;
	/**
	 * 1 si le message est supprim� du c�t� de l'exp�diteur, 0 sinon
	 * @var integer 
	 */
	var $is_deleted;
	/**
	 * Titre du minimail
	 * @var string 
	 */
	var $title;
	/**
	 * Corps du minimail
	 * @var string 
	 */
	var $message;
	/**
	 * Destinataires
	 * @var array 
	 */
	var $dest = array();

	
	/**
	 * Cr�e un objet Minimail � partir de la base de donn�es
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/17
	 * @param integer $id Id du minimail
	 */
	function createMinimailFromId ($id) {
		
		$dao = CopixDAOFactory::create("minimail_from");
		$obj = $dao->get($id);
		//print_r($this);
		$this->fillMinimail ($obj->from_id, $obj->from_login, $obj->title, $obj->message, "", $obj->is_deleted, $obj->date_send);
		$this->id = $id;
		// On cherche les destinataires
		$this->fillDestinataires ();
	}
	
	/**
	 * Remplit les destinataires � partir de la base de donn�es
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/17
	 */
	function fillDestinataires () {
		$this->dest = array();
		$dao = CopixDAOFactory::create("minimail_to");
		$dests = $dao->getListDest($this->id);
		//print_r($dests);
		if ($dests !== NULL) {
			while (list(,$dest) = each ($dests)) {
				$this->dest[] = $dest->to_login;
			}
		}
	}
	
	/**
	 * Remplit l'objet Minimail
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/17
	 * @param integer from_id Id utilisateur de l'exp�diteur
	 * @param string from_login Login de l'exp�diteur
	 * @param string title Titre du minimail
	 * @param string message Corps du minimail
	 * @param string dest_txt Le ou les destinataires (si plusieurs, s�par�s par "," ou ";") 
	 * @param integer is_deleted (option, par d�faut=0) 1 si le message est supprim� c�t� exp�diteur, 0 sinon
	 * @param string date_send (option, par d�faut=0) date d'envoi (si 0=maintenant)
	 */
	function fillMinimail ($from_id, $from_login, $title, $message, $dest_txt, $is_deleted=0, $date_send=0) {
		if ($date_send==0) $date_send = date("Y-m-d H:i:s");
		$this->from_id = $from_id;
		$this->from_login = $from_login;
		$this->date_send = $date_send;
		$this->title = $title;
		$this->message = $message;
		$this->is_deleted = $is_deleted;
		// D�coupage du pattern destinataires
		$dest_txt=str_replace(array(" "), "", $dest_txt);
		$dest_txt=str_replace(array(",",";"), ",", $dest_txt);
		$this->dest = explode (",", $dest_txt);
	}
	
	
}

?>