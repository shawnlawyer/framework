<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelSetActiveTrait {
    
    public static function updateActive($active = 0, $_model = null){
        
        ($_model == null)
            ? forward_static_call_array(array(static::$modeler,'model'),array())
            : forward_static_call_array(array(static::$modeler,'model'),array($_model)) ;
            
        $active = (intval($active) == 1) ? 1 : 0;
        
        static::$modeler::model()->updateField($active,'active');
        
        return static::$modeler::model();
    }
    
}
