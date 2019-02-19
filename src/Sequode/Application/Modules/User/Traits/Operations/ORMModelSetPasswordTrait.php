<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Foundation\Hashes;

trait ORMModelSetPasswordTrait {
    
    public static function updatePassword($password, $_model = null){
        
        $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $modeler::model()->updateField(Hashes::generateHash($password),'password');
        
        return $modeler::model();
    }
}