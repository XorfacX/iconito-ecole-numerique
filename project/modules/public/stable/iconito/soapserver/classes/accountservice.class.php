<?php

/**
 * Description of accountservice
 *
 * @author alemaire
 */
class accountservice extends enicService
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function create(soapAccountModel $account)
    {
        
    }
    
    /*
     * Creer l'account dans la table de liens
     */
    public function creerAccount($account_id, $school_id, $director_id) {
        $this->db->create(
                'module_account', array(
                    'id_account' => $this->db->quote($account_id),
                    'id_school' => $this->db->quote($school_id),
                    'id_director' => $this->db->quote($director_id)
                )
        );
        return 1;
    }
    
    /*
     * Recupere l'id de l'ecole liée a l'account
     */
    public function getSchoolFromAccount ($account_id)
    {
        return $this->db->query('SELECT id_school FROM module_account WHERE id_account='.$this->db->quote($account_id))->toInt ();
    }
    
    /*
     * Creer la classe dans la table de lien
     */
    public function creerAccountClass ($account_id, $class_id, $class)
    {
        
        $this->db->create(
             'module_account_class', array(
                 'id_account' => $this->db->quote($account_id),
                 'id_class' => $this->db->quote($class_id),
                 'id_class_EN' => $this->db->quote($class->classId),
                 'creation_date' => 'CURDATE()',
                 'validity_date' => 'ADDDATE(CURDATE(), 60)'
             )
        );
        
    }
    
    /*
     * Test d'existence dans la table de liens module_account
     */
    public function existeAccount($account, $school, $director)
    {
            return $this->db->query('
            SELECT id 
            FROM module_account 
            WHERE id_account='.$account.' AND id_school='.$school.' AND id_director='.$director);
    }
    
    /*
     * Validation de la classe
     */
    public function validerClass($class)
    {
            $this->db->query('
            UPDATE module_account_class
            SET validity_date='.$this->db->quote($class->validityDate).'
            WHERE id_account='.$this->db->quote($class->accountId).' AND id_class_EN='.$this->db->quote($class->classId));
        
        return 1;
    }
}

?>
