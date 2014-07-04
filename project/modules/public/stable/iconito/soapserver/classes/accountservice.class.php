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

    /*
     * insert in db account's datas
     */

    public function create($account_id, $school_id, $director_id)
    {
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
     * insert in db class account datas
     */

    public function createClass($account_id, $class_id, $class)
    {
        $this->db->create(
                'module_account_class', 
                array(
                    'id_account' => (int) $account_id,
                    'id_class_SUB' => (int) $class->classId,
                    'id_class_EN' => (int) $class_id,
                    'creation_date' => 'CURDATE()',
                    'validity_date' => $this->db->quote($class->validityDate)
                )
        );
    }
    
    /**
     * Activate coreprim for a specific class 
     * 
     * @param type $class_id
     */
    public function activateCoreprim($class_id)
    {
        $this->db->create(
              'module_coreprim_access',
              array('classroom_id', $class_id));  
    }

    public function cityDatasProxy($soapCity)
    {
        $city = new stdClass();
        $city->nom = utf8_decode($soapCity);
        $city->nomCanonique = Kernel::createCanon($city->nom);

        return $city;
    }

    public function schoolDatasProxy(soapSchoolModel $soapSchool)
    {
        $school = new stdClass();
        $school->nom = utf8_decode($soapSchool->name);
        $school->rne = $soapSchool->rne;
        $school->type = utf8_decode('Elémentaire');
        $school->siret = $soapSchool->siret;
        $school->adresse = $this->addressDatasProxy($soapSchool->address);

        return $school;
    }

    protected function addressDatasProxy(soapAddressModel $soapAddress)
    {
        $address = new stdClass();
        $address->numRue = "";
        $address->numSeq = "";
        $address->adresse1 = utf8_decode($soapAddress->address);
        $address->adresse2 = utf8_decode($soapAddress->additionalAddress);
        $address->codePostal = $soapAddress->postalCode;
        $address->commune = utf8_decode($soapAddress->city);
        return $address;
    }

    public function directorDatasProxy(soapDirectorModel $soapDirector)
    {
        $director = new stdClass();
        $director->nom = utf8_decode($soapDirector->name);
        $director->nomJf = ""; //TODO
        $director->prenom = utf8_decode($soapDirector->surname);
        $director->civilite = ($soapDirector->gender == 1) ? 'Monsieur' : 'Madame';
        $director->idSexe = $soapDirector->gender;
        $director->telDom = ""; //TODO
        $director->telGsm = ""; //TODO
        $director->telPro = $soapDirector->phone; //TODO
        $director->mail = $soapDirector->mail;
        return $director;
    }

    public function classDatasProxy(soapClassModel $soapClass)
    {
        $classDatas = new stdClass();
        $classDatas->nom = utf8_decode($soapClass->name);
        $classDatas->anneeScolaire = $soapClass->year;
        $classDatas->niveaux = $this->makeClassLevels($soapClass);
        $classDatas->validityDate = $soapClass->validityDate;

        return $classDatas;
    }
	
	public function getDirectorLogin($directorId)
	{
	}

    public function makeClassLevels(soapClassModel $soapClass)
    {
		$soapLevels = (is_array($soapClass->level->item)) ? $soapClass->level->item : array($soapClass->level->item);
        foreach ($soapLevels as $soapLevel) {
            $level = new stdClass();
            $level->niveau = $soapLevel;
            $level->type = $soapClass->type;
            $levels[] = $level;
        }
        unset($level);
        return $levels;
    }

    /*
     * Recupere l'id de l'ecole liée a l'account
     */
    public function getSchoolFromAccount($account_id)
    {
        return $this->db->query('SELECT id_school FROM module_account WHERE id_account=' . $this->db->quote($account_id))->toInt();
    }

    /*
     * Test d'existence dans la table de liens module_account
     */
    public function existsAccount($account, $school, $director)
    {
        return $this->db->query('
            SELECT id 
            FROM module_account 
            WHERE id_account=' . $account . ' AND id_school=' . $school . ' AND id_director=' . $director
        )->toInt();
    }

    /*
     * Validation de la classe
     */
    public function validateClass($class)
    {
        $this->db->query('
            UPDATE module_account_class
            SET validity_date=' . $this->db->quote($class->validityDate) . '
            WHERE id_account=' . $this->db->quote($class->accountId) . ' AND id_class_SUB =' . $this->db->quote($class->classId)
        );
    }

    /*
     * Modification du mot de passe du directeur
     */
    public function updateDirectorPassword($accountId, $password)
    {
		
        $directorId = $this->db->query('
            SELECT user_id
            FROM module_account 
            JOIN kernel_link_bu2user KLB2U ON KLB2U.bu_id=module_account.id_director 
            WHERE KLB2U.bu_type = \'USER_ENS\'
            AND id_account=' . $this->db->quote($accountId)
        )->toInt();
		if (empty($directorId))
			throw new Exception ('Aucun directeur ne correspond');

        $this->db->query('
            UPDATE dbuser
            SET password_dbuser=' . $this->db->quote($password) . '
			WHERE id_dbuser=' . $directorId
        );
    }

    public function makeDirectorLogin(soapDirectorModel $director)
    {
        return Kernel::createLogin(array('nom' => $director->name, 'prenom' => $director->surname, 'type' => 'USER_ENS'));
    }

}

?>
