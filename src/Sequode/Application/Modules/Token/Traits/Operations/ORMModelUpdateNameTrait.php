<?php

namespace Sequode\Application\Modules\Token\Traits\Operations;

trait ORMModelUpdateNameTrait {
    
    public static function updateName($name, $_model = null){
        
        $modeler = static::$modeler;

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $modeler::model()->name = str_replace(" ","_", $name);
        $modeler::model()->save();

        return $modeler::model();
        
    }
    
}