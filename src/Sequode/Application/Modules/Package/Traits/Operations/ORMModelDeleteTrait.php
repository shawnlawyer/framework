<?php

namespace Sequode\Application\Modules\Package\Traits\Operations;

trait ORMModelDeleteTrait {
    
    public static function delete($_model = null){
        
        $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $modeler::model()->delete($modeler::model()->id);
        
        return $modeler::model();
        
    }
    
}