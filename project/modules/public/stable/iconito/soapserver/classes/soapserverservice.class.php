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
     * @return returnSoapAccount
     */
    public function createAccount(soapAccountModel $account) {
        try {
            
            /*
             * City process
             */
            $schoolCityId = $this->kernelAPI->existeVille($account->school->address->city);
            
            if(empty($schoolCityId))
                $schoolCityId = $this->kernelAPI->creerVille(
                    1, 
                    $this->accountService->cityDatasProxy($account->school->address->city)
                );
				
            /*
             * School process
             */
            $schoolId = $this->kernelAPI->existeEcole($account->school->name, $account->school->address->city);

            if(empty($schoolId))
                $schoolId = $this->kernelAPI->creerEcole(
                    $schoolCityId,
                    $this->accountService->schoolDatasProxy($account->school)
                );

            /*
             * Director process
             */
            $directorId = $this->kernelAPI->existeDirecteur($account->school->director->name, $account->school->director->surname);
            
            if(empty($directorId)){
                $directorId = $this->kernelAPI->creerDirecteur(
                    $schoolId, 
                    $this->accountService->directorDatasProxy($account->school->director)
                );
                
                $directorLogin = $this->accountService->makeDirectorLogin($account->school->director);
                
                $this->kernelAPI->creerLogin(
                        'USER_ENS', 
                        $directorId, 
                        $directorLogin,
                        $account->school->director->password,
                        false
                );
            }

            /*
             * Account process
             */
            $idAccount = $this->accountService->existsAccount($account->id, $schoolId, $directorId);
            
            if(empty($idAccount))
                $idAccount = $this->accountService->create($account->id, $schoolId, $directorId);
            
            $return = new returnSoapAccount;
            $return->returnSoapDirector->login = $directorLogin;
            $return->returnSoapDirector->id = $directorId;
            return $return;
            
        }catch (Exception $e){
            throw new SoapFault('server', $e->getMessage());
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
        try{
            $classId = $this->kernelAPI->creerClasse(
                $this->accountService->getSchoolFromAccount($class->accountId), 
                $this->accountService->classDatasProxy($class)
            );
         
            $this->accountService->createClass($class->accountId, $classId, $class);
        
            return $classId;
		}catch(Exception $e){
			throw new SoapFault('server', $e->getMessage());
		}
    }

    /**
     * Activate class (remove trial period)
     * 
     * @param soapClassModel $class
     * @return int
     */
    public function validateClass(soapClassModel $class) 
    {
        $this->accountService->validateClass($class);
        
        return 1;
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
    
    /**
     * Director's phoneNumber
     * @var string
     */
    public $phone;
    
    /**
     * Director's password (md5 string)
     * @var string
     */
    public $password;

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
    
    /**
     * School's siret number
     * @var string
     */
    public $siret;

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
     * Class' year
     * @var int
     */
    public $year;
    
    /**
     * class' validity date
     * @var string
     */
    public $validityDate;

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

class returnSoapAccount
{
    /**
     * @var returnSoapDirector
     */
    public $returnSoapDirector;
    
    public function __construct()
    {
        $this->returnSoapDirector = new returnSoapDirector();
    }
}

class returnSoapDirector
{
    /**
     * Director's Login
     * @var string 
     */
    public $login;
    
    /**
     * Director's Id
     * @var int
     */
    public $id;
}

?>
