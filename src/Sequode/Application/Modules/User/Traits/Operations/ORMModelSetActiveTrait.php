<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

trait ORMModelSetActiveTrait {
    
    public static function updateActive($active = 0, $_model = null){
        $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $active = (intval($active) == 1) ? 1 : 0;
        
        $modeler::model()->active = $active;
        $modeler::model()->save();

        return $modeler::model();
    }
    
}
