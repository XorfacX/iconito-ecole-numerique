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
class soapserverservice
{
    
    public function __construct()
    {
        $this->accountService = enic::get('helpers')->service('accountservice');
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
    public function createAccount(soapAccountModel $account)
    {
        try{
        //make an account
        $this->accountService->create($account);
        
        //make or get the school's city
        $account->school->address->cityId = $this->cityService->GetOrCreate($account->school->address->city);
        
        //create the school
        $this->schoolService->create($account->school);
        
        }catch(accountException $e){
            throw new SoapFault('server', $e->getMessage);
        }catch(schoolException $e){
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
    public function createClass(soapClassModel $class)
    {
        throw new SoapFault('server', 'Erreur de test');
        file_put_contents('testsoap.txt', print_r($class, true));
        
        return 1;
    }
    
    /**
     * Activate class (remove trial period)
     * 
     * @param soapClassModel $class
     * @return int
     */
    public function validateClass(soapClassModel $class)
    {
        
    }
    
}


class soapAccountModel
{
    
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
    public function __construct()
    {
        $this->school = new soapSchoolModel();
    }
    
}

class soapCityModel
{
    /**
     * City's name
     * @var string
     */
    public $name;

}

class soapDirectorModel
{
    
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

class soapSchoolModel
{
    
    /**
     * School's name
     * @var string
     */
    public $name;
    
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
    
    public function __construct()
    {
        $this->address = new soapAddressModel();
        $this->director = new soapDirectorModel();
    }
}

class soapAddressModel
{
    
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

class soapClassModel
{
    
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
