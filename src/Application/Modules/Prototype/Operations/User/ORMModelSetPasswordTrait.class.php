<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

use Sequode\Foundation\Hashes;

trait ORMModelSetPasswordTrait {
    
    public static function updatePassword($password, $_model = null){
        
        ($_model == null)
            ? forward_static_call_array(array(static::$modeler,'model'),array())
            : forward_static_call_array(array(static::$modeler,'model'),array($_model)) ;
            
        static::$modeler::model()->updateField(Hashes::generateHash($password),'password');
        
        return static::$modeler::model();
    }
}