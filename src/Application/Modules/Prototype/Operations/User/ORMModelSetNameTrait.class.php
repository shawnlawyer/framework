<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelSetNameTrait {
    
    public static function updateName($username, $_model = null){
        
        ($_model == null)
            ? forward_static_call_array(array(static::$modeler,'model'),array())
            : forward_static_call_array(array(static::$modeler,'model'),array($_model)) ;
            
        static::$modeler::model()->updateField($username, 'username');
        
        return static::$modeler::model();
    }
    
}