<?php

namespace Sequode\Application\Modules\Token\Traits\Operations;

trait ORMModelDeleteTrait {
    
    public static function delete($_model = null){
        
        $modeler = static::$modeler;
        
        ($_model == null)
            ? forward_static_call_array([$modeler, 'model'], [])
            : forward_static_call_array([$modeler, 'model'], [$_model]) ;
            
        $modeler::model()->delete($modeler::model()->id);
        
        return $modeler::model();
    
    }
    
}