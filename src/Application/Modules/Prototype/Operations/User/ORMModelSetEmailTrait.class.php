<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelSetEmailTrait {
    
    public static function updateEmail($email, $_model = null){
        
        ($_model == null)
            ? forward_static_call_array(array(static::$modeler,'model'),array())
            : forward_static_call_array(array(static::$modeler,'model'),array($_model)) ;
            
        static::$modeler::model()->updateField($email,'email');
        
        return static::$modeler::model();
    }
    
}