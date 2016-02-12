<?php

namespace Sequode\Application\Modules\Prototype\Operations;

use Sequode\Model\Module\Registry as ModuleRegistry;

trait ORMModelUpdateNameTrait {
    public static function updateName($name, $_model = null){
        
        ($_model == null)
            ? forward_static_call_array(array(static::$modeler,'model'),array())
            : forward_static_call_array(array(static::$modeler,'model'),array($_model)) ;
            
        static::$modeler::model()->updateField(str_replace(" ","_",$name),'name');
        
        return $modeler::model();
    }
}