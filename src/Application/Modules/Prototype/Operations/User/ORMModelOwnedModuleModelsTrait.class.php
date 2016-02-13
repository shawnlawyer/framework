<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

use Sequode\Model\Module\Registry as ModuleRegistry;

trait ORMModelOwnedModuleModelsTrait {
    
	public static function getOwnedModels($module_registry_key, $_model = null, $fields='id'){
        
        $modeler = static::$modeler;
        
        ($_model == null)
            ? forward_static_call_array(array($modeler, 'model'), array())
            : forward_static_call_array(array($modeler, 'model'), array($_model)) ;
            
        $module_modeler = ModuleRegistry::model($module_registry_key)->modeler;
        
        $where = array();
        $where[] = array('field'=>'owner_id','operator'=>'=','value'=>$modeler::model()->id);
        
        $model = new $module_modeler::$model;
        $model->getAll($where, $fields);
        
        return $model;
	}
    
}