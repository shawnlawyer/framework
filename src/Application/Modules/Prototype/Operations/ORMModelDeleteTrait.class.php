<?php

namespace Sequode\Application\Modules\Prototype\Operations\Token;

use Sequode\Model\Module\Registry as ModuleRegistry;

trait ORMModelDeleteTrait {
    public static function delete($_model = null){
        
        ($_model == null)
            ? forward_static_call_array(array(static::$modeler,'model'),array())
            : forward_static_call_array(array(static::$modeler,'model'),array($_model)) ;
            
        static::$modeler::model()->delete($modeler::model()->id);
        
        return $modeler::model();
    }
}