<?php

namespace Sequode\Application\Modules\Package\Traits\Operations;

trait ORMModelSetPropertiesTrait {
    
    public static function updatePackageSequode($sequode_id, $_model = null){
        
        $modeler = static::$modeler;
        
        ($_model == null)
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model)) ;
            
        $modeler::model()->updateField($sequode_id,'sequode_id');
        
        return $modeler::model();
        
	}
    
}