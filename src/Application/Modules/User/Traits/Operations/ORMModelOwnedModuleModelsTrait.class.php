<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Model\Module\Registry as ModuleRegistry;

trait ORMModelOwnedModuleModelsTrait {
    
	public static function getOwnedModels($registry_key, $_model = null, $fields='id'){
        
        $modeler = static::$modeler;
        
        ($_model == null)
            ? forward_static_call_array(array($modeler, 'model'), array())
            : forward_static_call_array(array($modeler, 'model'), array($_model)) ;
            
        $module_modeler = ModuleRegistry::model($registry_key)->modeler;
        
        $where = array();
        $where[] = array('field'=>'owner_id','operator'=>'=','value'=>$modeler::model()->id);
        
        $model = new $module_modeler::$model;
        $model->getAll($where, $fields);
        
        return $model;
	}
    
}