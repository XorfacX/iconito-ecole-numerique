<?php

/**
 * Surcharge de la DAO Kernel_bu_ville
 * 
 * @package Iconito
 * @subpackage Kernel
 */

class DAOKernel_bu_ville {

	/**
	 * Retourne une ville par son canon
	 *
	 * @param int $canon Canon d'une ville
	 * @return CopixDAORecordIterator
	 */
	public function getByCanon ($canon) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('canon', '=', $canon);
		
		return $this->findBy ($criteria);
	}
	
	public function getByIdGrville ($id_grville) {
	  
	  $criteria = _daoSp ();
		$criteria->addCondition ('id_grville', '=', $id_grville);
		
		return $this->findBy ($criteria);
	}

}


?>