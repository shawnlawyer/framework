<?php

namespace Sequode\Application\Modules\Token\Traits\Operations;

use Sequode\Model\Module\Registry as ModuleRegistry;

trait ORMModelGetOwnedModelsTrait {
    
	public static function getOwnedModels($user_model, $fields='id'){
        
        $modeler = static::$modeler;
        
        $where = array();
        $where[] = array('field'=>'owner_id','operator'=>'=','value'=>$user_model->id);
        
        $model = new $modeler::$model;
        $model->getAll($where, $fields);
        
        return $model;
	}
    
}