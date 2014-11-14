<?php

    class ActionGroupDefault extends enicActionGroup
    {
        public function __construct()
        {
            parent::__construct();
            $this->service =& $this->service('rssEtagereService');
        }

        public function processGo()
        {
            return $this->redirect('');
        }

        public function beforeAction ()
        {
        _currentUser()->assertCredential ('group:[current_user]');
        }

        public function processDefault()
        {
            _classInclude('sysutils|coreprimService');
            $node = _sessionGet('myNode');
            $coreprim = new coreprimService();
            if($coreprim->classHasAccess($node['id']) == 0){
                return CopixActionGroup::process('genericTools|Messages::getError', array('message' => CopixI18N::get('kernel|kernel.error.noRights'), 'back' => CopixUrl::get()));
            }
            
            $ppo = new CopixPPO();
            
            if(!$this->service->loadxml()){
                return $this->error('rssetagere.notfound', true, '||');
            }

            $ppo->title = $this->service->getTitle();
            $ppo->desc = $this->service->getDescription();
            $ppo->items = $this->service->getItems();
            $ppo->isEns = ($this->user->type == 'USER_ENS');
            $ppo->listUrl = $this->helpers->config('rssetagere|list_url');
            return _arPPO($ppo, 'default.tpl');
        }

    }
