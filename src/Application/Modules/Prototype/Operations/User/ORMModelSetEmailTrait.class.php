<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelSetEmailTrait {
    
    public static function updateEmail($email, $_model = null){
        
        $modeler = static::$modeler;
        
        ($_model == null)
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model)) ;
            
        $modeler::model()->updateField($email,'email');
        
        return $modeler::model();
    }
    
}