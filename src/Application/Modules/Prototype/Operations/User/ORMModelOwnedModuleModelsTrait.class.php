<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelOwnedModuleModelsTrait {
    
	public static function getOwnedModels($module_registry_key, $_model = null, $fields='id'){
        
        ($_model == null)
            ? forward_static_call_array(array(static::$modeler,'model'),array())
            : forward_static_call_array(array(static::$modeler,'model'),array($_model)) ;
            
        $where = array();
        $where[] = array('field'=>'owner_id','operator'=>'=','value'=>static::$modeler::model()->id);
        
        $model = new $modeler::$model;
        $model->getAll($where, $fields);
        
        return $model;
	}
    
}