<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

trait ORMModelSetNameTrait {
    
    public static function updateName($username, $_model = null){
        
        $modeler = static::$modeler;
        
        ($_model == null)
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model)) ;
            
        $modeler::model()->updateField($username, 'username');
        
        return $modeler::model();
    }
    
}