<?php

namespace Sequode\Application\Modules\User;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Collections {
    public static $package = 'User';
    public static function search($search_object, $limit=100){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $search_object->position = urldecode($search_object->position);
        $search_object->field = urldecode($search_object->field);
        if(!in_array($search_object->position, array('=%','%=%','%=','='))){
            $search_object->position = '=%';
        }
        if(!in_array($search_object->field, array('username','email'))){
            $search_object->field = 'username';
        }
        $results = array();
        $where = array();
        if($search_object->active != 'all'){
            $where[] = array('field'=>'active','operator'=>'=','value'=>$search_object->active);
        }
        if($search_object->role != 'all'){
            $where[] = array('field'=>'role_id','operator'=>'=','value'=>$search_object->role);
        }
        $where[] = array('field'=>$search_object->field,'operator'=>$search_object->position,'value'=>$search_object->search);
        $_model = new $modeler::$model;
        $_model->getAll($where,'id,username',false, $limit);
        $results = array_merge($results,$_model->all);
        unset($_model);
        return $results;
    }
}