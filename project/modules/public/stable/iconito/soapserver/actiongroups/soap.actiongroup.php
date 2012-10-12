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
            $soap->setWsdlCache(false);
            $soap->setUri($this->url('soapserver|soap|Server'));
            $soap->setClass('soapserverservice');
            $soap->handle();
            
            return _arNone();
        }
        
        public function processTest()
        {
            
            $client = new Zend_Soap_Client($this->url('soapserver|soap|WSDL'));
            $client->setWsdlCache(false);
            $class = new soapClassModel();
            $class->name = 'Vasi Tavue';
            $class->accountId = 1;
            $class->classId = 6;
            $class->level = 7;
            $class->type = 8;
            
            try{
                $i = $client->createClass($class);
            }catch (Exception $e){
                echo 1;
                echo $e;
            }
            echo '===';
            echo $i;
            return _arNone();
        }
        
    }