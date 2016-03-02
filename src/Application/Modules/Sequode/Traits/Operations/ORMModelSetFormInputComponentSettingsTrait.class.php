<?php

namespace Sequode\Application\Modules\Sequode\Traits\Operations;

trait ORMModelSetFormInputComponentSettingsTrait {
    
    public static function updateComponentSettings($type, $member, $input_object, $_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        ($_model == null)
            ? forward_static_call_array(array($modeler, 'model'), array())
            : forward_static_call_array(array($modeler, 'model'), array($_model)) ;
            
        switch($type){
            
            case 'input':
            case 'property':
                $object_member = $type.'_form_object';
                break;
            default:
                return $modeler::model();
                
        }
        
		$form_object = json_decode($modeler::model()->$object_member);
		$component = new \Sequode\Application\Modules\FormInputs\Model;
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
        
        $modeler::model()->updateField(json_encode($form_object),$object_member);
        
        self::maintenance();
            
        return $modeler::model();
            
    }
    
}