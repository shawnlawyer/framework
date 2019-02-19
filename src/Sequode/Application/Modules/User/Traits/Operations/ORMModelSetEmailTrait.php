<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

trait ORMModelSetEmailTrait {
    
    public static function updateEmail($email, $_model = null){
        
        $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $modeler::model()->updateField($email,'email');
        
        return $modeler::model();
    }
    
}