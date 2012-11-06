<?php

    class ActionGroupSoap extends enicActionGroup
    {
        public function __construct()
        {
            parent::__construct();
            $this->service = $this->service('soapserverservice');
            
            enic::zend_load('Soap/Server');
            enic::zend_load('Soap/Wsdl');
            enic::zend_load('Soap/AutoDiscover');
            enic::zend_load('Soap/Wsdl/Strategy/ArrayOfTypeSequence');
            enic::zend_load('Soap/Server');
            enic::zend_load('Soap/Client');
            
        }

        public function processWSDL()
        {
            $autodiscover = new Zend_Soap_AutoDiscover(new Zend_Soap_Wsdl_Strategy_ArrayOfTypeSequence());
            
            $autodiscover->setClass('soapserverservice');
            
            $autodiscover->setUri($this->url('soapserver|soap|Server'));
            
            $autodiscover->handle();
            
            return _arNone();
            
        }
        
        public function processServer()
        {
            
            $soap = new Zend_Soap_Server($this->url('soapserver|soap|WSDL'));
            $soap->setUri($this->url('soapserver|soap|Server'));
            $soap->setClass('soapserverservice');
            $soap->setWsdlCache(false);
            $soap->handle();
            return _arNone();
            
        }
        
        public function processTest()
        {
            
            $client = new Zend_Soap_Client($this->url('soapserver|soap|WSDL'));
            $client->setWsdlCache(false);
            
            $class1 = new soapClassModel();
            $class1->name = 'Vasi Tavue';
            $class1->accountId = 1;
            $class1->classId = 6;
            $class1->level = array(7);
            $class1->type = 8;
            $class1->year = 2011;
            $class1->validityDate = '2013-08-31';
            
            $class = new soapClassModel();
            $class->name = 'Vasi Tavue';
            $class->accountId = 1;
            $class->classId = 6;
            $class->level = array(7);
            $class->type = 8;
            $class->year = 2011;
            $class->validityDate = '2013-08-31';
            
            $directeur = new soapDirectorModel();
            $directeur->name ="jeanClaude";
            $directeur->surname ="leCompte";
            $directeur->mail = "lecompte@caramail.fr";
            $directeur->gender = 1;
            
            $adress = new soapAddressModel();
            $adress->address ="4 rue des cochons";
            $adress->additionalAddress = "";
            $adress->city = "Larochelle";
            $adress->postalCode = "76788";
            
            $school = new soapSchoolModel();
            $school->name = "DesChamps";
            $school->address = $adress;
            $school->director = $directeur;
            
            $account = new soapAccountModel();
            $account->id = 1;
            $account->school = $school;
            
            try{
                
                $j = $client->createAccount($account);
                $i = $client->createClass($class);
                $k = $client->validateClass($class1);
                
            }catch (Exception $e){
                echo $e;
            }
            echo '===';
            var_dump($i);
            var_dump($j);
            var_dump($k);
            return _arNone();
        }
        
        public function processLocalTest()
        {
            $this->myservice = enic::get('helpers')->service('soapserver|soapserverservice');
            
            $class = new soapClassModel();
            $class->name = 'Vasi Tavue';
            $class->accountId = 1;
            $class->classId = 6;
            $class->level = 7;
            $class->type = 8;
            $class->year = 2011;
             $date =  new DateTime('2012-08-31');
            $class->validityDate = $date->format("Y-m-d H:i:s");
            
            $directeur = new soapDirectorModel();
            $directeur->name ="jeanClaude";
            $directeur->surname ="leCompte";
            $directeur->mail = "lecompte@caramail.fr";
            $directeur->gender = 1;
            $directeur->password = md5(123456);
            $directeur->phone = '0123456742';
            
            $adress = new soapAddressModel();
            $adress->address ="4 rue des cochons";
            $adress->additionalAddress = "";
            $adress->city = "Larochelle";
            $adress->postalCode = "76788";
            
            $school = new soapSchoolModel();
            $school->name = "DesChamps";
            $school->address = $adress;
            $school->director = $directeur;
            $school->rne = 123456789;
            $school->siret = '1234567898';
            
            $account = new soapAccountModel();
            $account->id = 1;
            $account->school = $school;
            var_dump($this->myservice->accountService->create(1,2,3));
            try{
            $j = $this->myservice->createAccount($account);
            $i = $this->myservice->createClass($class);
            $k = $this->myservice->validateClass($class);
            }
            catch (Exception $e){
                echo $e;
            }
            
            return _arNone();
        }
        
    }