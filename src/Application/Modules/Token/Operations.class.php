<?php

namespace Sequode\Application\Modules\Token;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Application\Modules\Prototype\Operations\ORMModelUpdateNameTrait;
use Sequode\Application\Modules\Prototype\Operations\ORMModelDeleteTrait;

class Operations {
    
    use 
        ORMModelUpdateNameTrait,
        ORMModelDeleteTrait;
    
    public static $modeler = Modeler::class;
    
    public static function getModel($value = null, $by = null, $owner_id = null){
        $_model = new static::$modeler::$model;
        switch($by){
            case 'id':
            case 'name':
                break;
            default:
                $by = 'id';
                break;
        }
        if($value != null){
            $where[] = array('field'=>$by,'operator'=>'=','value'=>$value);
        }
        if($owner_id != null){
            $where[] = array('field'=>'owner_id','operator'=>'=','value'=>$owner_id);
        }
        $_model->getAll($where,'id',false,1);
        $id = false;
        foreach($_model->all as $key => $object){
            $id = $object->id;
			break;
        }
        if($id != false){
            $modeler::exists($id,'id');
            return static::$modeler::model();
        }
        return false;   
	}
}