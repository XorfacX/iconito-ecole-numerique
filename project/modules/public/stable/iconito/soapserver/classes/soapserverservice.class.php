<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of soapserverservice
 *
 * @author alemaire
 */
class soapserverservice {

    public function __construct() {
        $this->accountService = enic::get('helpers')->service('soapserver|accountservice');
        $this->kernelAPI = enic::get('helpers')->service('kernel|kernel_api');
    }

    /**
     * Create :
     *  City
     *  School
     *  Director
     * 
     * @param soapAccountModel $account
     * @return int
     */
    public function createAccount(soapAccountModel $account) {
        try {
            
            /*
             * Storing useful structures
             */
            $school = $account->school;
            $school_id;
            
            $school_director = $school->director;
            $school_director_id;
            
            $school_adress = $account->school->address;
            
            $school_city = $school_adress->city;
            $school_city_id;
            
            
            
           /*
            * Creating an object for creerEcole() purposes
            */
            $adresse = new stdClass();
            $adresse->numRue = "";
            $adresse->numSeq = "";
            $adresse->adresse1 = $school_adress->address;
            $adresse->adresse2 = $school_adress->additionalAddress ;
            $adresse->codePostal = $school_adress->postalCode ;
            $adresse->commune = $school_city ;
            
            /*
             * Creating an object for creerVille() purposes
             */
            $villeObj = new stdClass();
            $villeObj->nom = $school_city;
            $villeObj->nomCanonique = $school_city;
            
            /*
             * Creating the city if it does not exists
             * and storing it's id
             */
            $school_city_id = $this->kernelAPI->existeVille($school_city);
            
            if(empty($school_city_id))
                $school_city_id = $this->kernelAPI->creerVille(1, $villeObj);
            
            /*
             * Getting the school's id if exists
             */
            $school_id = $this->kernelAPI->existeEcole($school->name, $school_city);
            
            /*
             * Testing entries and creating the school if it does not exists
             */
            if(empty($school_id))
            {
                
                $school_data = new stdClass();
                $school_data->nom = $school->name;
                $school_data->rne = $school->rne;
                $school_data->type = "";
                $school_data->adresse = $adresse;
                
                $school_id = $this->kernelAPI->creerEcole($school_city_id, $school_data);
            }      
            
            /*
             * Getting the school's associated director's id if exists
             */
            $school_director_id = $this->kernelAPI->existeDirecteur($school_director->surname, $school_director->name);
            
            /*
             * Creating the director if it does not exists
             */
            if(empty($school_director_id))
            {
                
                $director_data = new stdClass();
                $director_data->nom = $school_director->surname;
                $director_data->nomjf = ""; //TODO
                $director_data->prenom = $school_director->name;
                $director_data->civilite = ""; //TODO
                $director_data->idSexe = $school_director->gender;
                $director_data->telDom = ""; //TODO
                $director_data->telGsm = ""; //TODO
                $director_data->telPro = ""; //TODO
                $director_data->mail = $school_director->mail;
                
                
                $school_director_id = $this->kernelAPI->creerDirecteur($school_id, $director_data);
            }
            
            /*
             * Creates the entry in the link table
             */
            $res = $this->accountService->creerAccount($account->id, $school_id, $school_director_id);
            
            return $res;
            
        } catch (accountException $e) {
            throw new SoapFault('server', $e->getMessage);
        } catch (schoolException $e) {
            throw new SoapFault('server', $e->getMessage);
        }
    }
    
    /**
     * Create :
     *  Class with trial period
     * 
     * @param soapClassModel $class
     * @return int
     */
    public function createClass(soapClassModel $class) {
        
        $class_data = new stdClass();
        $class_data->nom = $class->name;
        $class_data->anneeScolaire = "";
        $class_data->niveaux = array($class->level);
         
        $class_school_id = $this->accountService->getSchoolFromAccount($class->accountId);

        $class_id = $this->kernelAPI->creerClasse($class_school_id, $class_data);
        
        var_dump($class->accountId);
        var_dump($class->classId);
         
        $this->accountService->creerAccountClass($class->accountId, $class_id);
        
        return $class_id;
    }

    /**
     * Activate class (remove trial period)
     * 
     * @param soapClassModel $class
     * @return int
     */
    public function validateClass(soapClassModel $class) {
        
    }

}

class soapAccountModel {

    /**
     * Account's ID
     * @var int
     */
    public $id;

    /**
     * Account's School Object
     * @var soapSchoolModel
     */
    public $school;

    /**
     * @return void
     */
    public function __construct() {
        $this->school = new soapSchoolModel();
    }

}

class soapDirectorModel {

    /**
     * Director's name
     * @var string
     */
    public $name;

    /**
     * Director's surname
     * @var string
     */
    public $surname;

    /**
     * Director's sexe
     * @example 1 for male, 2 for female, 0 for unknow
     * @var int
     */
    public $gender;

    /**
     * Director's mail
     * @var string
     */
    public $mail;

}

class soapSchoolModel {

    /**
     * School's name
     * @var string
     */
    public $name;
    
    /**
     * School's RNE
     * @var string
     */
    public $rne;

    /**
     * School's Address
     * @var soapAddressModel
     */
    public $address;

    /**
     * School's director object
     * @var soapDirectorModel
     */
    public $director;

    public function __construct() {
        $this->address = new soapAddressModel();
        $this->director = new soapDirectorModel();
    }

}

class soapAddressModel {

    /**
     * Address default line
     * @var string
     */
    public $address;

    /**
     * Address complementary line
     * @var string
     */
    public $additionalAddress;

    /**
     * Postal code
     * @var int
     */
    public $postalCode;

    /**
     * City
     * @var string 
     */
    public $city;

}

class soapClassModel {

    /**
     * Class' name
     * @var string
     */
    public $name;

    /**
     * Define what account is linked to current class
     * @var int
     */
    public $accountId;

    /**
     * Unique identifier
     * @var int
     */
    public $classId;

    /**
     * Class' level
     * @var int[]
     */
    public $level;

    /**
     * Class' type
     * @var int 
     */
    public $type;

}

?>
