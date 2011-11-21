<?php

    class ActionGroupDefault extends enicActionGroup {

        public function __construct(){
            parent::__construct();
            $this->service =& $this->service('rssEtagereService');
        }
        
        public function processGo(){
            return $this->redirect('');
        }

        public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');
        }

        public function processDefault(){
            $ppo = new CopixPPO();
            
            if(!$this->service->loadxml()){
                $this->error('xml.notfound');
            }
            
            $ppo->title = $this->service->getTitle();
            $ppo->desc = $this->service->getDescription();
            $ppo->items = $this->service->getItems();
            return _arPPO($ppo, 'default.tpl');
        }

    }