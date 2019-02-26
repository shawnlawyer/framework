<?php

namespace Sequode\Application\Modules\Sequode\Traits\Operations;

trait ORMModelSetPropertySwitchesTrait {
        
	public static function updateTenancy($value = 0, $_model = null){
        
       $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        switch(intval($value)){
            
            case 1:
                break;
            default:
                $value = 0;
            
        }
        
        //$modeler::model()->updateField($value,'safe');
        
        return $modeler::model();
        
	}
    
	public static function updateSharing($value = 0, $_model = null){
        
       $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        switch(intval($value)){
            case 1:
                break;
            default:
                $value = 0;
            
        }
        
        $modeler::model()->updateField($value,'shared');
        
        return $modeler::model();
        
	}
    
	public static function updateIsPalette($value = 0, $_model = null){
        
       $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        switch(intval($value)){
            
            case 1:
                break;
            default:
                $value = 0;
            
        }
        
        $modeler::model()->updateField($value,'palette');
        
        return $modeler::model();
        
	}
    
	public static function updateIsPackage($value = 0, $_model = null){
        
       $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        switch(intval($value)){
            
            case 1:
                break;
            default:
                $value = 0;
            
        }
        
        $modeler::model()->updateField($value,'package');
        
        return $modeler::model();
        
	}
    
}