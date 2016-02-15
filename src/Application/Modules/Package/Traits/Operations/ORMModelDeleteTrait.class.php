<?php

namespace Sequode\Application\Modules\Package\Traits\Operations;

trait ORMModelDeleteTrait {
    
    public static function delete($_model = null){
        
        $modeler = static::$modeler;
        
        ($_model == null)
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model)) ;
            
        $modeler::model()->delete($modeler::model()->id);
        
        return $modeler::model();
        
    }
    
}