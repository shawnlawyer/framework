<?php

namespace Sequode\Application\Modules\Sequode\Traits\Operations;

trait ORMModelSetNameTrait {
    
    public static function updateName($name, $_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        ($_model == null)
            ? forward_static_call_array([$modeler, 'model'], [])
            : forward_static_call_array([$modeler, 'model'], [$_model]) ;
            
        $modeler::model()->updateField(str_replace(" ","_",$name),'name');
            
        return $modeler::model();
            
    }
    
}