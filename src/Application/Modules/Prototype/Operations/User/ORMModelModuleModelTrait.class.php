<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelModuleModelTrait {
    
	public static function getModel($value = null, $by = null, $owner_id = null){
        
        $modeler = static::$modeler;
        
        $model = new $modeler::$model;
        
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
        $model->getAll($where,'id',false,1);
        $id = false;
        foreach($model->all as $key => $object){
            $id = $object->id;
			break;
        }
        if($id != false){
            $modeler::exists($id,'id');
            return $modeler::model();
        }
        return false;   
	}
    
}