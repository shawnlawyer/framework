<?php

namespace Sequode\Application\Modules\Session;

class Collections {
    
    public static $modeler = Modeler::class;
    
    public static function search($_i, $limit=100){
        
        $modeler = static::$modeler;
        
        $_i->position = urldecode($_i->position);
        $_i->field = urldecode($_i->field);
        $_i->search = urldecode($_i->search);
        
        if(!in_array($_i->position, ['=%','%=%','%=','='])){
            $_i->position = '=%';
        }
        
        if(!in_array($_i->field, ['name','ip_address'])){
            $_i->field = 'name';
        }
        
        $where = [];
        $where[] = ['field'=> $_i->field,'operator'=>$_i->position,'value'=>$_i->search];
        
        $_model = new $modeler::$model;
        $_model->getAll($where, 'id,name', $limit);
        $results = $_model->all;
        
        unset($_model);
        
        return $results;
    }
}