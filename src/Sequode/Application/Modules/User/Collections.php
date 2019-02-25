<?php

namespace Sequode\Application\Modules\User;

use Sequode\Application\Modules\User\Module;

class Collections {
    
    public static $module = Module::class;
    
    public static function search($search_object, $limit=100){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $search_object->position = urldecode($search_object->position);
        $search_object->field = urldecode($search_object->field);
        if(!in_array($search_object->position, ['=%','%=%','%=','='])){
            $search_object->position = '=%';
        }
        if(!in_array($search_object->field, ['name','email'])){
            $search_object->field = 'name';
        }
        $results = [];
        $where = [];
        if($search_object->active != 'all'){
            $where[] = ['field'=>'active','operator'=>'=','value'=>$search_object->active];
        }
        if($search_object->role != 'all'){
            $where[] = ['field'=>'role_id','operator'=>'=','value'=>$search_object->role];
        }
        $where[] = ['field'=>$search_object->field,'operator'=>$search_object->position,'value'=>$search_object->search];
        $_model = new $modeler::$model;
        $_model->getAll($where, 'id,name', $limit);
        $results = array_merge($results, $_model->all);
        unset($_model);
        return $results;
    }
}