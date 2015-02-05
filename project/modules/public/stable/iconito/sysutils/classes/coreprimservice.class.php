<?php


class coreprimService
{
    
    public function listSchools()
    {
        $schoolsList = _doQuery('SELECT numero as id, nom as name FROM kernel_bu_ecole');
        
        return $schoolsList;
        
    }
    
    public function listClassRoomsBySchools($schools)
    {
        $actualClassRooms = $this->getAllSchoolId();
        foreach($schools as $school){
            $school->classRooms = _doQuery('SELECT id, nom as name FROM kernel_bu_ecole_classe WHERE ecole = ?', array($school->id));
            
            $checked = 0;
            $nbClassrooms = 0;
            foreach($school->classRooms as $classrooms)
                if(in_array($classrooms->id, $actualClassRooms))
                    $classrooms->checked = true;
        }
        
        return $schools;
    }
    
    public function classHasAccess($classId)
    {
        if (CopixConfig::exists('default|rssEtagereFilterClassEnabled') && CopixConfig::get('default|rssEtagereFilterClassEnabled')) {            
            $return = _doQuery('SELECT COUNT(*) AS enable FROM module_coreprim_access WHERE classroom_id = ?', array($classId));
            $hasAccess = $return[0]->enable;
        } else {
            $hasAccess = 1;
        }
        
        return $hasAccess;
    }
    
    public function save($classroomsId)
    {
        $this->removeAll();
        foreach($classroomsId as $id){
            $query = 'INSERT INTO module_coreprim_access (classroom_id) VALUE (?)';    
            _doQuery($query, array($id));
        }
        
    }
    
    public function removeAll()
    {
        _doQuery('TRUNCATE TABLE module_coreprim_access');
    }
    
    public function getAllSchoolId()
    {
        $allId = _doQuery('SELECT classroom_id AS id FROM module_coreprim_access');
        $return = array();
        foreach($allId as $id)
            $return[] = $id->id;
        
        return $return;
    }
}

?>
