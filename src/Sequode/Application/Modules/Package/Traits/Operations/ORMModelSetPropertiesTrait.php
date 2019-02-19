<?php

namespace Sequode\Application\Modules\Package\Traits\Operations;

trait ORMModelSetPropertiesTrait {
    
    public static function updatePackageSequode($sequode_id, $_model = null){
        
        $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $modeler::model()->updateField($sequode_id,'sequode_id');
        
        return $modeler::model();
        
	}
    
}