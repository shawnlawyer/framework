<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Model\Module\Registry as ModuleRegistry;

trait ORMModelOwnedModuleModelsTrait {
    
	public static function getOwnedModuleModels($module_registry_key, $_model = null, $fields='id'){
        
        $modeler = static::$modeler;
        
        ($_model == null)
            ? forward_static_call_array([$modeler, 'model'], [])
            : forward_static_call_array([$modeler, 'model'], [$_model]) ;
        
        $module = ModuleRegistry::module($module_registry_key);
        $module_modeler = $module::model()->modeler;
        
        $where = [];
        $where[] = ['field'=>'owner_id','operator'=>'=','value'=>$modeler::model()->id];
        
        $model = new $module_modeler::$model;
        $model->getAll($where, $fields);
        
        return $model;
	}
    
}