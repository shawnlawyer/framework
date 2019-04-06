<?php

namespace Sequode\Application\Modules\Sequode;

use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;

class Collections {
    
    public static $module = Module::class;
    const Module = Module::class;

    public static function search($_i, $limit=100){

        extract((static::Module)::variables());
        
        if($_i->coded == 0 && $_i->sequenced == 0){
            return [];
        }elseif($_i->coded == 1 && $_i->sequenced == 0){
            $shared_where = ['field'=>'usage_type','operator'=>'=','value'=>'0'];
        }elseif($_i->coded == 0 && $_i->sequenced == 1){
            $shared_where = ['field'=>'usage_type','operator'=>'=','value'=>'1'];
        };
        $_i->position = urldecode($_i->position);
        if(!in_array($_i->position, ['=%','%=%','%=','='])){
            $_i->position = '=%';
        }
        if(AccountAuthority::isSystemOwner()){
            $where = [];
            if(isset($shared_where)){
                $where[] = $shared_where;
            }
            $where[] = ['field'=>'name','operator'=>$_i->position,'value'=>$_i->search];
            $_model = new $modeler::$model;
            $_model->getAll($where,'id,name', $limit);
            $results = $_model->all;
            unset($_model);
        }else{
            $results = [];
            if($_i->my_sequodes == 1){
                $where = [];
                if(isset($shared_where)){
                    $where[] = $shared_where;
                }
                $where[] = ['field'=>'name','operator'=>$_i->position,'value'=>$_i->search];
                $where[] = ['field'=>'owner_id','operator'=>'=','value'=> AccountModeler::model()->id];
                
                $_model = new $modeler::$model;
                $_model->getAll($where, 'id,name', $limit);
                $results = array_merge($results, $_model->all);
                unset($_model);
            }
            if($_i->shared_sequodes == 1){
                $where = [];
                
                if(isset($shared_where)){
                    $where[] = $shared_where;
                }
                $where[] = ['field'=>'name','operator'=>$_i->position,'value'=>$_i->search];
                $where[] = ['field'=>'owner_id','operator'=>'!=','value'=> AccountModeler::model()->id];
                $where[] = ['field'=>'shared','operator'=>'=','value'=>'1'];
                
                $_model = new $modeler::$model;
                $_model->getAll($where, 'id,name', $limit);
                $results = array_merge($results, $_model->all);
                unset($_model);
            }
        }
        
        return $results;
        
    }
}