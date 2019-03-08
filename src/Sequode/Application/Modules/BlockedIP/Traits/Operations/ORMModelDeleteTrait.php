<?php

namespace Sequode\Application\Modules\BlockedIP\Traits\Operations;

trait ORMModelDeleteTrait {
    
    public static function delete($_model = null){
        
        $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $modeler::model()->delete();
        
        return $modeler::model();
    }
}