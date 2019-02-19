<?php

namespace Sequode\Application\Modules\Package\Traits\Operations;

trait ORMModelSetNameTrait {
    
    public static function updateName($name, $_model = null){
        
        $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $modeler::model()->updateField(str_replace(" ","_",$name),'name');
        
        return $modeler::model();
        
	}
    
}