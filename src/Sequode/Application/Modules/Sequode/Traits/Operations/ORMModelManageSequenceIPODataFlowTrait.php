<?php

namespace Sequode\Application\Modules\Sequode\Traits\Operations;

trait ORMModelManageSequenceIPODataFlowTrait {
            
    public static function updateValue($type = false, $map_key = 0, $value = null, $_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        if (intval($modeler::model()->usage_type) === 0){
            
            return false;
            
        }
        
        switch($type){
            
            case 'input':
                $stack = 'Inp_Obj';
                $object_map_member = 'input_object_map';
                $default_map_member = 'default_input_object_map';
                $object_detail_member = 'input_object_detail';
                break;
            case 'property':
                $stack = 'Prop_Obj';
                $object_map_member = 'property_object_map';
                $default_map_member = 'default_property_object_map';
                $object_detail_member = 'property_object_detail';
                break;
            default:
                return false;
                
        }
        
		
        $object_map = $modeler::model()->$object_map_member;
        $default_map = $modeler::model()->$default_map_member;
        $object_detail = $temp_model->$object_detail_member;
        if(!(intval($map_key > 0) && isset($default_map[$map_key]))){
            
            return $modeler::model();
            
        }
        
        $location_object = $default_map[$map_key];
        $run_maintenance = (empty($object_map[$map_key]->Value)) ? true : false;
        
        $temp_model = new $modeler::$model;
		$temp_model->exists($sequence[$location_object->Key - 1],'id');
        
        $member = $location_object->Member;
        $object_detail = $temp_model->$object_detail_member;
        $detail_member = $object_detail->$member;
        
        $object_map[$map_key]->Stack = $stack;
        $object_map[$map_key]->Key = $location_object->Key;
        $object_map[$map_key]->Member = $location_object->Member;
        /*
        $detail_member->type = $type;
        $detail_member->required = $required;
        $detail_member->default_value = $default_value;
        */
        switch($detail_member->type){
            
            case 'binary':
                $value = (binary) $value;
                break;
            case 'int':
            case 'integer':
                $value = (integer) $value;
                break;
            case 'bool':
            case 'boolean':
                $value = (boolean) $value;
                break;
            case 'float':
            case 'double':
            case 'real':
                $value = (float) $value;
                break;
            case 'array':
                $value = @json_decode($value,true);
                break;
            case 'object':
                $value = @json_decode($value);
                break;
                
        }
        
        $object_map[$map_key]->Value = $value;

        $modeler::model()->$object_map_member = $kit::removeKeys($object_map);

        $modeler::model()->save();
        
        if($run_maintenance){
            
            return self::maintenance();
            
        }
        return $modeler::model();
        
    }
    
    public static function addInternalConnection($receiver_type = false, $transmitter_key = 0, $receiver_key = 0, $_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        if($receiver_key == 0 || $transmitter_key == 0){return $modeler::model();}
        if(intval($modeler::model()->usage_type) === 0 ){return $modeler::model();}
        switch($receiver_type){
            case 'input':
            case 'property':
                $object_member = $receiver_type . '_object';
                $object_map_member = $receiver_type . '_object_map';
                break;
            default:
                return $modeler::model();
        }
        
        $object_map = $modeler::model()->$object_map_member;
        $type_object = $modeler::model()->$object_member;
        $output_map = $modeler::model()->default_output_object_map;
        
        if(count($object_map) <= $receiver_key  ||  count($output_map) <= $transmitter_key ){
            return $modeler::model();
        }
        if( $object_map[$receiver_key]->Key <= $output_map[$transmitter_key]->Key){
            return $modeler::model();
        }
        
        $object_map[$receiver_key]->Stack = $output_map[$transmitter_key]->Stack;
        $object_map[$receiver_key]->Member = $output_map[$transmitter_key]->Member;
        $object_map[$receiver_key]->Key = $output_map[$transmitter_key]->Key;
        $object_map[$receiver_key]->Value = null;
       
        $modeler::model()->$object_map_member = $object_map;

        $modeler::model()->save();
        
        return self::maintenance();
        
    }
    public static function addExternalConnection($connection_type = false, $transmitter_key = 0, $receiver_key = 0, $_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        if(intval($modeler::model()->usage_type) === 0 ){
            return $modeler::model();
        }
        switch($connection_type){
            
            case 'input':
            case 'property':
            case 'output':
                $object_member = $connection_type . '_object';
                $object_map_member = $connection_type . '_object_map';
                $default_object_map_member = 'default_' . $connection_type . '_object_map';
                break;
            default:
                return $modeler::model();
                
        }
        
        $internal_key = ($connection_type == 'output') ? $transmitter_key : $receiver_key ;
        $external_key = ($connection_type == 'output') ? $receiver_key : $transmitter_key ;
        
        if($internal_key == 0){
            
            return $modeler::model();
            
        }
        
        $object_map = $modeler::model()->$object_map_member;
        $type_object = $modeler::model()->$object_member;
        $default_object_map = $modeler::model()->$default_object_map_member;
        
        if($external_key > count($type_object)){
            
            return $modeler::model();
            
        }
        
        if($external_key == 0){
            
            $external_member_name = $kit::makeUniqueMemberName($default_object_map[$internal_key]->Member, $object_map);
            
        }else{
            
            $i = 1;
            foreach($type_object as $member => $value){
                
                if($external_key == $i){
                    
                     $external_member_name =  $member;
                    break;
                    
                }
                
                $i++;
                
            }
            
        }
        
        $object_map[$internal_key]->Stack = $default_object_map[$internal_key]->Stack;
        $object_map[$internal_key]->Member = $external_member_name;
        $object_map[$internal_key]->Key = 0;
        $object_map[$internal_key]->Value = null;

        $modeler::model()->$object_map_member = $object_map;

        $modeler::model()->save();
        
		return self::maintenance();
        
    }
    
    public static function removeReceivingConnection($connection_type = false, $restore_key = 0, $_model = null){
        
        $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        if(intval($modeler::model()->usage_type) === 0 ){
            return $modeler::model();
        }
        switch($connection_type){
            case 'input':
            case 'property':
            case 'output':
                $object_member = $connection_type . '_object';
                $object_map_member = $connection_type . '_object_map';
                $default_object_map_member = 'default_' . $connection_type . '_object_map';
                break;
            default:
                return $modeler::model();
        }
        
        if($restore_key == 0){
            return $modeler::model();
        }
        
        $object_map = $modeler::model()->$object_map_member;
        $type_object = $modeler::model()->$object_member;
        $default_object_map = $modeler::model()->$default_object_map_member;
        
        if($connection_type != 'output'){
            
            if(count($default_object_map) <= $restore_key){
                
                return $modeler::model();
                
            }
            
        }else{
            
            if(count($type_object) < $restore_key){
                
                return $modeler::model();
                
            }
            $i = 1;
            foreach($type_object as $member => $value){
                
                if($restore_key == $i){
                    
                    $external_member_name =  $member;
                    break;
                    
                }
                $i++;
                
            }
            foreach($object_map as $key => $map_location){
                
                if($map_location->Key == 0 && $map_location->Member == $external_member_name){
                    
                    $restore_key =  $key;
                    break;
                    
                }
                
            }
            
        }
        
        $object_map[$restore_key] = $default_object_map[$restore_key];

        $modeler::model()->$object_map_member = $object_map;

        $modeler::model()->save();
        
		return self::maintenance();
        
    }
}