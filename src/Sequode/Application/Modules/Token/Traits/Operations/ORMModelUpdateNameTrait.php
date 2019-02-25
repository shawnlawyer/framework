<?php

namespace Sequode\Application\Modules\Token\Traits\Operations;

trait ORMModelUpdateNameTrait {
    
    public static function updateName($name, $_model = null){
        
        $modeler = static::$modeler;
        
        ($_model == null)
            ? forward_static_call_array([$modeler, 'model'], [])
            : forward_static_call_array([$modeler, 'model'], [$_model]) ;
            
        $modeler::model()->updateField(str_replace(" ","_",$name),'name');
        
        return $modeler::model();
        
    }
    
}