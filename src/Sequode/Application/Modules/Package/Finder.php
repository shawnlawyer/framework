<?php
namespace Sequode\Application\Modules\Package;

use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;

class Finder {
    
    public static $modeler = Modeler::class;
    
    public static function search($_i, $limit=100){
        $modeler = static::$modeler;
        $_i->position = urldecode($_i->position);
        //$_i->field = urldecode($_i->field);
        if(!in_array($_i->position, ['=%','%=%','%=','='])){
            $_i->position = '=%';
        }
       // if(!in_array($_i->field, array('name','ip_address'))){
            $_i->field = 'name';
        //}
        
        if(AccountAuthority::isSystemOwner()){
            $where = [];
            if(isset($shared_where)){
                $where[] = $shared_where;
            }
            $where[] = ['field'=> $_i->field,'operator'=>$_i->position,'value'=>$_i->search];
            $_model = new $modeler::$model;
            $_model->getAll($where, 'id,name', $limit);
            $results = $_model->all;
            unset($_model);
        }else{
            $where = [];
            if(isset($shared_where)){
                $where[] = $shared_where;
            }
            $where[] = ['field'=> $_i->field,'operator'=>$_i->position,'value'=>$_i->search];
            $where[] = ['field'=>'owner_id','operator'=>'=','value'=> AccountModeler::model()->id];
            
            $_model = new $modeler::$model;
            $_model->getAll($where, 'id,name', $limit);
            $results = $_model->all;
            unset($_model);
        }
        return $results;
    }
}