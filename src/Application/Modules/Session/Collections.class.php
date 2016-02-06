<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Collections {
    public static $package = 'Session';
    public static function search($_i, $limit=100){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $_i->position = urldecode($_i->position);
        $_i->field = urldecode($_i->field);
        if(!in_array($_i->position, array('=%','%=%','%=','='))){
            $_i->position = '=%';
        }
        if(!in_array($_i->field, array('username','ip_address'))){
            $_i->field = 'username';
        }
        $results = array();
        $where = array();
        $where[] = array('field'=>$_i->field,'operator'=>$_i->position,'value'=>$_i->search);
        $_model = new $modeler::$model;
        $_model->getAll($where,'id, username',false, $limit);
        $results = array_merge($results,$_model->all);
        unset($sessions_model);
        return $results;
    }
}