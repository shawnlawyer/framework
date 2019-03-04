<?php

namespace Sequode\Application\Modules\Sequode\Traits\Operations;

use Sequode\Application\Modules\FormInput\Model as FormInputModel;

trait ORMModelSetFormInputComponentSettingsTrait {
    
    public static function updateComponentSettings($type, $member, $input_object, $_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        switch($type){
            
            case 'input':
            case 'property':
                $object_member = $type.'_form_object';
                break;
            default:
                return $modeler::model();
                
        }
        
		$form_object = json_decode($modeler::model()->$object_member);
		$component = new FormInputModel;
		if(!$component->exists($input_object->Component,'name')){
            
            $component->exists('str','name');
            
        }
        
		$form_object_member = (object) null;
		$component_form_object = json_decode($component->component_form_object);
		foreach($component_form_object as $loop_member => $loop_value){
            
			if(isset($input_object->$loop_member)){
                
					$form_object_member->$loop_member = $input_object->$loop_member;
                    
			}
            
		}
		$form_object->$member = $form_object_member;
        
        $modeler::model()->$object_member = json_encode($form_object);
        $modeler::model()->save();
        self::maintenance();
            
        return $modeler::model();
            
    }
    
}