<?php

namespace Sequode\Application\Modules\Sequode\Traits\Operations;

trait ORMModelSetNameTrait {
    
    public static function updateName($name, $_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $modeler::model()->name = str_replace(" ","_", $name);
        $modeler::model()->save();

        return $modeler::model();
            
    }
    
}