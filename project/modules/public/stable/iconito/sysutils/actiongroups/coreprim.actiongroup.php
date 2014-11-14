<?php
_classInclude('coreprimService');


class ActionGroupCoreprim extends enicActionGroup
{

    public function __construct()
    {
        parent::__construct();
        $this->service =& $this->service('coreprimService');
    }
    
    public function beforeAction()
    {
        _currentUser()->assertCredential('group:[current_user]');
    }

    public function processDefault()
    {
        if (!Kernel::isAdmin())
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => CopixI18N::get('kernel|kernel.error.noRights'), 'back' => CopixUrl::get()));
    
        $classList = $this->service->listClassRoomsBySchools(
            $this->service->listSchools()
        );

        $ppo = new CopixPPO();
        $ppo->classRoomsBySchools = $classList;
        $ppo->title = $this->i18n('coreprim.title');
        $ppo->activate = $this->i18n('coreprim.activate');
        $ppo->activateForSchool = $this->i18n('coreprim.activate.school');
        $ppo->action = $this->url('sysutils|coreprim|save');
        $ppo->noClassRooms = $this->i18n('coreprim.noClassRooms');
        return _arPPO($ppo, 'coreprim-admin.tpl');
        
    }
    
    public function processSave()
    {
        if (!Kernel::isAdmin())
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => CopixI18N::get('kernel|kernel.error.noRights'), 'back' => CopixUrl::get()));
        
        $classRoomsId = (is_null($this->request('classrooms'))) ? array() : $this->request('classrooms');

        $this->service->save($classRoomsId);
        
        return _arRedirect('default');
    }

}