<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelSetLastSignInTrait {
    
    public static function updateLastSignIn($time=false, $_model = null){
        
        ($_model == null)
            ? forward_static_call_array(array(static::$modeler,'model'),array())
            : forward_static_call_array(array(static::$modeler,'model'),array($_model)) ;
            
        static::$modeler::model()->updateField(($time === false) ? time() : $time ,'last_sign_in');
        
        return static::$modeler::model();
    }
    
}