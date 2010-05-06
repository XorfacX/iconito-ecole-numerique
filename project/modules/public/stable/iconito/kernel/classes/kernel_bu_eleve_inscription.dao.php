<?php

/**
 * Surcharge de la DAO Kernel_bu_eleve_inscription
 * 
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_eleve_inscription {
	
	/**
	 * Retourne l'inscription d'un élève pour une école donnée
	 *
	 * @param int $studentId Identifiant d'un élève
	 * @param int $schoolid  Identifiant d'un école
	 * @return CopixDAORecordIterator
	 */
	public function getByStudentAndSchool ($studentId, $schoolId) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('eleve', '=', $studentId);
		$criteria->addCondition ('etablissement', '=', $schoolId);
		
		$results = $this->findBy ($criteria);
		
		return isset ($results[0]) ? $results[0] : false;
	}
	
	/**
	 * Retourne les enregistrements d'un élève
	 *
	 * @param int $studentId Identifiant d'un élève
	 * @return CopixDAORecordIterator
	 */
	public function getByStudent ($studentId) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('eleve', '=', $studentId);
		
		return $this->findBy ($criteria);
	}
}
