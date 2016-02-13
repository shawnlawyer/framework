<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

use Sequode\Foundation\Hashes;

trait ORMModelSetPasswordTrait {
    
    public static function updatePassword($password, $_model = null){
        
        $modeler = static::$modeler;
        
        ($_model == null)
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model)) ;
            
        $modeler::model()->updateField(Hashes::generateHash($password),'password');
        
        return $modeler::model();
    }
}