<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

trait ORMModelSetNameTrait {
    
    public static function updateName($name, $_model = null){
        
        $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $modeler::model()->name = $name;
        $modeler::model()->save();

        return $modeler::model();
    }
    
}