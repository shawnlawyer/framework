<?php

namespace Sequode\Application\Modules\Sequode\Traits\Operations;

use Sequode\Application\Modules\Sequode\Authority as SequodeAuthority;

trait ORMModelManageCodeTrait {
    
    public static function deleteCode($_model = null){
        
        $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        if(!SequodeAuthority::isCode()){
            return $modeler::model();
        }
        
        $modeler::model()->delete();
        
        return $modeler::model();
        
    }
    
	public static function buildSequodeCodeNodeOffMineObject($_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $mine_object = json_decode($modeler::model()->mine_object);
        $detail = (object) null;
        $detail->description = $mine_object->description;
        $printable_name = $mine_object->name;
        $input_object = (object) null;
        $last_key = false;
        foreach($mine_object->parameters as $key_2 => $object_2){
            
            if($object_2->name != ''){
                
                if($object_2->name == '...'){
                    
                    if(is_numeric(substr($mine_object->parameters[$last_key]->name, -1, 1))){
                        
                        $object_2->name = substr_replace($mine_object->parameters[$last_key]->name, "", -1).'_n';
                        
                    }else{
                        
                        $object_2->name = $mine_object->parameters[$last_key]->name.'_n';
                        
                    }
                    
                }
                
                $member = $object_2->name;
                $input_object->$member = $object_2->default_value;
                
            }
            
            $last_key = $key_2;
            
        }
        
        $property_object = (object) null;
        $property_object->Run_Process = false;
        $output_object = (object) null;
        $output_object->Success = false;
        if($mine_object->return_type != 'void' && $mine_object->return_type != ''){
            
            $member = $mine_object->return_type;
            $output_object->$member = null;
            
        }
        
        $input_object_detail = (object) null;
        foreach($mine_object->parameters as $key_2 => $object_2){
            
            if($object_2->name != ''){
                
                $member = $object_2->name;
                $input_object_detail->$member = $kit::makeProcessObjectDetailMember('input', $object_2->type ,$object_2->name, $object_2->default_value ,'str', $object_2->required);
                
            }
            
        }
        
        $property_object_detail = (object) null;
        $member = 'Run_Process';
        $property_object_detail->$member = $kit::makeDefaultProcessObjectDetailMember($member);
        $output_object_detail = (object) null;
        $member = 'Success';
        $output_object_detail->$member = $kit::makeDefaultProcessObjectDetailMember($member);
        if($mine_object->return_type != 'void' && $mine_object->return_type != ''){
            
            $member = $mine_object->return_type;
            $output_object_detail->$member = $kit::makeProcessObjectDetailMember('output', $mine_object->return_type ,$mine_object->return_type, null);
            
        }

        $modeler::model()->detail = $detail;
        $modeler::model()->input_object = $input_object;
        $modeler::model()->property_object = $property_object);
        $modeler::model()->output_object = $output_object;
        $modeler::model()->input_object_detail = $input_object_detail;
        $modeler::model()->property_object_detail = $property_object_detail;
        $modeler::model()->output_object_detail = $output_object_detail;
        $modeler::model()->save();

        return $modeler::model();
        
    }
    
	public static function processCodedSequodeSetupData($_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
		$detail = $modeler::model()->detail;
		$input_object_detail = $modeler::model()->input_object_detail;
		$property_object_detail = $modeler::model()->property_object_detail;
		$output_object_detail = $modeler::model()->output_object_detail;
		$input_object = $modeler::model()->input_object;
		$property_object = $modeler::model()->property_object;
		$output_object = $modeler::model()->output_object;
		
		$modeler::model()->input_form_object = $input_form_object;
		$modeler::model()->property_form_object = $property_form_object;
		$modeler::model()->input_object_map = [];
		$modeler::model()->property_object_map = [];
		$modeler::model()->output_object_map = [];
		$modeler::model()->process_description_node = (object) null;
        $modeler::model()->process_description_node = $kit::makeSequodeProcessDescriptionNode($modeler::model());
        $modeler::model()->save();
        
		return $modeler::model();
        
    }
	
}