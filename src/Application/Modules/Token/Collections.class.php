<?php

namespace Sequode\Application\Modules\Token;

use Sequode\Application\Modules\Token\Module;
    
class Collections {
    
    public static $module = Module::class;
    
    public static function search($_i, $limit=100){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $_i->position = urldecode($_i->position);
        //$_i->field = urldecode($_i->field);
        if(!in_array($_i->position, array('=%','%=%','%=','='))){
            $_i->position = '=%';
        }
       // if(!in_array($_i->field, array('name','ip_address'))){
            $_i->field = 'name';
        //}
        
        if(\Sequode\Application\Modules\Account\Authority::isSystemOwner()){
            $where = array();
            if(isset($shared_where)){
                $where[] = $shared_where;
            }
            $where[] = array('field'=> $_i->field,'operator'=>$_i->position,'value'=>$_i->search);
            $_model = new $modeler::$model;
            $_model->getAll($where, 'id,name', $limit);
            $results = $_model->all;
            unset($_model);
        }else{
            $where = array();
            if(isset($shared_where)){
                $where[] = $shared_where;
            }
            $where[] = array('field'=> $_i->field,'operator'=>$_i->position,'value'=>$_i->search);
            $where[] = array('field'=>'owner_id','operator'=>'=','value'=>\Sequode\Application\Modules\Account\Modeler::model()->id);
            
            $_model = new $modeler::$model;
            $_model->getAll($where, 'id,name', $limit);
            $results = $_model->all;
            unset($_model);
        }
        return $results;
    }
}