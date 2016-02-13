<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelSetLastSignInTrait {
    
    public static function updateLastSignIn($time=false, $_model = null){
        $modeler = static::$modeler;
        
        ($_model == null)
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model)) ;
            
        $modeler::model()->updateField(($time === false) ? time() : $time ,'last_sign_in');
        
        return $modeler::model();
    }
    
}