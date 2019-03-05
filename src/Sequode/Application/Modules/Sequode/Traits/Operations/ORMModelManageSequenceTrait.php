<?php

namespace Sequode\Application\Modules\Sequode\Traits\Operations;

use Sequode\Foundation\Hashes;
use Sequode\Application\Modules\Sequode\Authority as SequodeAuthority;

trait ORMModelManageSequenceTrait {
    
    public static function maintenance($_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        if(SequodeAuthority::isSequence()){
            
            $modeler::model()->input_object =  $kit::makeProcessObject('input');
            $modeler::model()->property_object =  $kit::makeProcessObject('property');
            $modeler::model()->output_object =  $kit::makeProcessObject('output');
            //$modeler::model()->process_instance_object =  $kit::makeProcessInstanceObject());
            $modeler::model()->input_object_map = $kit::removeKeys($modeler::model()->input_object_map);
            $modeler::model()->property_object_map = $kit::removeKeys($modeler::model()->property_object_map);
            $modeler::model()->output_object_map = $kit::removeKeys($modeler::model()->output_object_map);
            
        }
        
        $modeler::model()->input_form_object = $kit::pruneFormObject('input');
        $modeler::model()->property_form_object = $kit::pruneFormObject('property');
        $modeler::model()->input_form_object = $kit::updateFormObjectMembers('input');
        $modeler::model()->property_form_object = $kit::updateFormObjectMembers('property');
        
        $modeler::model()->input_object_detail = $kit::updateProcessObjectDetails('input');
        $modeler::model()->property_object_detail = $kit::updateProcessObjectDetails('property');
        $modeler::model()->output_object_detail = $kit::updateProcessObjectDetails('output');
        $modeler::model()->input_object_detail = $kit::pruneProcessObjectDetails('input');
        $modeler::model()->property_object_detail = $kit::pruneProcessObjectDetails('property');
        $modeler::model()->output_object_detail = $kit::pruneProcessObjectDetails('output');
        
        if(SequodeAuthority::isSequence()){
            
            $modeler::model()->default_input_object_map = $kit::makeDefaultSequenceObjectMap('input', $modeler::model());
            $modeler::model()->default_property_object_map = $kit::makeDefaultSequenceObjectMap('property', $modeler::model());
            $modeler::model()->default_output_object_map = $kit::makeDefaultSequenceObjectMap('output', $modeler::model());
            
        }
        $modeler::model()->save();
        self::regenerateProcessDescriptionNode();
        
        return $modeler::model();
        
    }
    
    public static function regenerateProcessDescriptionNode($_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $modeler::model()->process_description_node = $kit::makeSequodeProcessDescriptionNode();
        $modeler::model()->save();

        return $modeler::model();
        
    }
    
    public static function newSequence($owner_id = 0){

        $modeler = static::$modeler;
        $kit = static::$kit;            
        
        $modeler::model()->create(substr(Hashes::uniqueHash(),0,15), '', 1, 1);
        $modeler::model()->name = substr(Hashes::uniqueHash($modeler::model()->id.$modeler::model()->name),0,15);
        $modeler::model()->sequence_type = 1;
        $modeler::model()->sequence = [];
        $modeler::model()->grid_areas = [];
        $modeler::model()->input_object = $kit::makeDefaultProcessObject('input');
        $modeler::model()->input_object_map = $kit::makeDefaultSequenceObjectMap('input', $modeler::model()));
        //$modeler::model()->process_instance_object = $kit::makeDefaultProcessInstanceObject($modeler::model());
        $modeler::model()->input_form_object = (object) [];
        $modeler::model()->output_object = $kit::makeDefaultProcessObject('output');
        $modeler::model()->output_object_map = $kit::makeDefaultSequenceObjectMap('output', $modeler::model());
        $property_object_detail = (object) null;
        $member = 'Run_Process';
        $property_object_detail->$member = $kit::makeDefaultProcessObjectDetailMember($member);
        $modeler::model()->property_object_detail = $property_object_detail;
        $modeler::model()->property_object = $kit::makeDefaultProcessObject('property');
        $modeler::model()->property_object_map = $kit::makeDefaultSequenceObjectMap('property', $modeler::model());
        $modeler::model()->property_form_object = (object) null;
        $modeler::model()->input_object_detail = (object) null;
        /*
        $output_object_detail = (object) null;
        $member = 'Success';
        $output_object_detail->$member = $kit::makeDefaultProcessObjectDetailMember($member);
        $modeler::model()->output_object_detail = $output_object_detail;
        */
        $modeler::model()->output_object_detail = (object) null;
        $modeler::model()->owner_id = $owner_id;
        //$modeler::model()->safe = 0;
        //`$modeler::model()->level = 0;
        $modeler::model()->detail = (object)["display_name" => $modeler::model()->name];
        $modeler::model()->save();

        self::maintenance();
        
        return $modeler::model();
        
	}
    
    public static function makeSequenceCopy($owner = 0, $_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        $name = $modeler::model()->name . ' Copy ' . ($modeler::model()->times_cloned + 1);
        $printable_name = (trim($modeler::model()->printable_name) != '') ? trim($modeler::model()->printable_name) . ' Copy ' . ($modeler::model()->times_cloned + 1) : $name ;
        
        $model_copy = new $modeler::$model;
        $model_copy->create($name, $printable_name, 1, 1);
        $model_copy->cloned_from_id = $modeler::model()->id;
        $model_copy->sequence_type = 1;
        $model_copy->sequence = $modeler::model()->sequence;
        //$model_copy->safe = $modeler::model()->safe;
        //$model_copy->level = $modeler::model()->level;
        $model_copy->detail = $modeler::model()->detail;
        $model_copy->grid_areas = $modeler::model()->grid_areas;
        $model_copy->input_object = $modeler::model()->input_object;
        $model_copy->input_object_detail = $modeler::model()->input_object;
        $model_copy->input_object_map = $modeler::model()->input_object_map;
        $model_copy->input_form_object = $modeler::model()->input_form_object;
        $model_copy->output_object = $modeler::model()->output_object;
        $model_copy->output_object_detail = $modeler::model()->input_object;
        $model_copy->output_object_map = $modeler::model()->output_object_map;
        $model_copy->property_object = $modeler::model()->property_object;
        $model_copy->property_object_detail = $modeler::model()->input_object;
        $model_copy->property_object_map = $modeler::model()->property_object_map;
        $model_copy->property_form_object = $modeler::model()->property_form_object;
        $model_copy->owner_id = $owner,'owner_id');
        $model_copy->times_cloned = 0;
        $model_copy->save();
        $modeler::model()->times_cloned + 1;
        $modeler::model()->save();

		self::maintenance($model_copy);
        
        return $modeler::model();
        
	}
	public static function makeDefaultSequencedSequode( $_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        if(!SequodeAuthority::isSequence()){return $modeler::model();}
        
        $modeler::model()->grid_areas = $kit::defaultGridAreas(count($modeler::model()->sequence));
		$modeler::model()->input_object = (object) null;
		$modeler::model()->property_object = (object) null;
		$modeler::model()->output_object = (object) null;
		$modeler::model()->input_form_object = (object) null;
		$modeler::model()->property_form_object = (object) null;
		$modeler::model()->process_description_node = (object) null;
		$modeler::model()->input_object_map = $kit::removeKeys($kit::makeDefaultSequenceObjectMap('input', $modeler::model()));
		$modeler::model()->property_object_map = $kit::removeKeys($kit::makeDefaultSequenceObjectMap('property', $modeler::model()));
		$modeler::model()->output_object_map = $kit::removeKeys($kit::makeDefaultSequenceObjectMap('output', $modeler::model()));
        $modeler::model()->input_object_detail = (object) null;
        $modeler::model()->property_object_detail = (object) null;
        $modeler::model()->output_object_detail = (object) null;
        
		self::maintenance();
        
		return $modeler::model();
        
	}
    
    public static function deleteSequence($_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        if(!SequodeAuthority::isSequence()){return $modeler::model();}
        $modeler::model()->delete();
        return $modeler::model();
        
    }
    
	public static function emptySequence($_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
		$modeler::model()->sequence = [];
		$modeler::model()->save();

		self::makeDefaultSequencedSequode();
        
        return $modeler::model();
        
    }
    
	public static function addToSequence($add_model_id, $position = 0, $position_tuner = null, $grid_modifier = null, $_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
		if($position_tuner != null ){ $position_tuner = intval($position_tuner);}; 
		if($grid_modifier != null ){ $grid_modifier = intval($grid_modifier);}; 
		
		$sequence = $modeler::model()->sequence;
        $position = (count($sequence) == 0) ? 0 : $kit::getSequencePosition($position, $sequence, 1);
		
		$sequence_map = $kit::makeUpdateSequenceInputMap($sequence);
		$sequence_map = $kit::addToUpdateSequenceInputMap($sequence_map, $add_model_id, $position);
        
        if(count($sequence) == 0){
            
            self::updateSequence($sequence_map);
            return self::makeDefaultSequencedSequode();
            
        }else{
            
            $grid_areas = $modeler::model()->grid_areas;
            $grid_areas = $kit::addToGridArea($position, $grid_areas, $modeler::model());
            
            if(count($sequence) != 0){
                
                $grid_areas = $kit::tuneGridAreaPosition($position, $grid_areas, $position_tuner, $modeler::model());
                
            }
            
            self::updateSequence($sequence_map);
            
            $modeler::model()->grid_areas = $grid_areas;
            
            if($grid_modifier > 0) {

                $grid_areas = $kit::modifyGridAreas($position, $grid_areas, $modeler::model());

                $modeler::model()->grid_areas = $grid_areas;

            }

            $modeler::model()->save();

            self::maintenance();
            
            return $modeler::model();

        }
        
    }
    
	public static function reorderSequence($from_position = 0, $to_position = 0, $position_tuner = null, $grid_modifier = null, $_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
		$sequence = $modeler::model()->sequence;
		$from_position = $kit::getSequencePosition($from_position, $sequence);
		$to_position = $kit::getSequencePosition($to_position, $sequence);
        $sequence_map = $kit::makeUpdateSequenceInputMap($sequence);
        $sequence_map = $kit::reorderUpdateSequenceInputMap($sequence_map ,$from_position, $to_position);
		
        $grid_areas = $modeler::model()->grid_areas;
		$grid_areas = $kit::moveFromGridAreaToGridArea($from_position, $to_position, $grid_areas, $modeler::model());
		$grid_areas = $kit::tuneGridAreaPosition($to_position, $grid_areas, $position_tuner, $modeler::model());
        
		self::updateSequence($sequence_map);
        
		$modeler::model()->grid_areas = $grid_areas;
        
        if($grid_modifier > 0){
            
            $grid_areas = $kit::modifyGridAreas($to_position, $grid_areas, $modeler::model());
            
            $modeler::model()->grid_areas = $grid_areas;
            
        }

        $modeler::model()->save();
        
        self::maintenance();
        
        return $modeler::model();
        
    }
    
	public static function removeFromSequence($position, $_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
		$sequence = $modeler::model()->sequence;
		$position = $kit::getSequencePosition($position, $sequence);
        $sequence_map = $kit::makeUpdateSequenceInputMap($sequence);
        $sequence_map = $kit::removeFromUpdateSequenceInputMap($sequence_map,$position);
        
		$grid_areas = $modeler::model()->grid_areas;
		$grid_areas = $kit::removeFromGridArea($position, $grid_areas, $modeler::model());
        
		self::updateSequence($sequence_map);
        
		$modeler::model()->grid_areas = $grid_areas;
		$modeler::model()->save();

        self::maintenance();
        
		return $modeler::model();
        
    }
    
    public static function updateSequence($new_sequence_map, $_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
		
		$old_sequence = $modeler::model()->sequence;
		$new_sequence = [];
		$old_sequence_maps = [];
		
		foreach( $old_sequence as $key => $value ){
			$old_sequence_maps[$key] = ['input_object_map' => [], 'property_object_map' => [], 'output_object_map' => []];
		}
		
		$new_sequence = [];
		foreach( $new_sequence_map as $key => $object ){
			$new_sequence[] = $object->id;
		}
        
		$removeKey = 0;
		if( count( $old_sequence ) > count( $new_sequence ) ){
			foreach( $old_sequence as $key => $value ){
				if($old_sequence[$key] != $new_sequence[$key]){
					$removeKey = $key + 1;
					break;
				}
			}
		}
		
		$new_keys = [];
		foreach( $new_sequence_map as $key => $object ){
			if(isset($object->order)){
				$new_keys[$object->order + 1] = $key + 1;
			}
		}
		
		$new_sequence_maps = [];
		foreach( $new_sequence as $key => $value ){
			$new_sequence_maps[$key] = ['input_object_map' => [], 'property_object_map' => [], 'output_object_map' => []];
			$default_sequence_maps[$key] = ['input_object_map' => [], 'property_object_map' => [], 'output_object_map' => []];
		}
        
		$old_input_object_map = $modeler::model()->input_object_map;
		$root_input_map_object = array_shift($old_input_object_map);
        
		$old_property_object_map = $modeler::model()->property_object_map;
		$root_property_map_object = array_shift($old_property_object_map);
		
		$old_output_object_map = j$modeler::model()->output_object_map;
		$root_output_map_object = array_shift($old_output_object_map);
		
		$object_cache = [];
		$loop_sequode = new $modeler::$model;
		
        
		foreach( $old_sequence as $key => $value ){
			if(!array_key_exists($value, $object_cache)){
				$object_cache[$value] = new $modeler::$model;
				$object_cache[$value]->exists($value, 'id');
			}
			
			$loop_sequence_map = [];
			$loop_input_object = $object_cache[$value]->input_object;
			foreach( $loop_input_object as $member ){
				$loop_map_object = array_shift($old_input_object_map);
				$loop_sequence_map[] = $loop_map_object;
			}
			$old_sequence_maps[$key]['input_object_map'] = $loop_sequence_map;
			
			$loop_sequence_map = [];
			$loop_property_object = $object_cache[$value]->property_object;
			foreach( $loop_property_object as $member ){
				$loop_map_object = array_shift($old_property_object_map);
				$loop_sequence_map[] = $loop_map_object;
			}
			$old_sequence_maps[$key]['property_object_map'] = $loop_sequence_map;
			
			$loop_sequence_map = [];
			$loop_output_object = $object_cache[$value]->output_object;
			foreach( $loop_output_object as $member ){
				$loop_map_object = array_shift($old_output_object_map);
				$loop_sequence_map[] = $loop_map_object;
			}
			$old_sequence_maps[$key]['output_object_map'] = $loop_sequence_map;
			
		}
        $addKey = 0;
		foreach( $new_sequence_map as $key => $object ){
			if(!isset($object->order)){
                $addKey = $key + 1;
                break;
            }
		}
        
		foreach( $new_sequence_map as $key => $object ){
			if(!array_key_exists($object->id, $object_cache)){
				$object_cache[$object->id] = new $modeler::$model;;
				$object_cache[$object->id]->exists($object->id,'id');
			}
			if(!isset($object->order)){
				$loop_object = $object_cache[$object->id]->input_object;
				$loop_sequence_map = [];
				foreach($loop_object as $member => $value){
					$loop_sequence_map[] = $kit::makeMapLocationObject('input','x',$member,$value);
				}
			}else{
				$loop_sequence_map = $old_sequence_maps[$object->order]['input_object_map'];
			}
			$new_sequence_maps[$key]['input_object_map'] = $loop_sequence_map;
			
			if(!isset($object->order)){
				$loop_object = $object_cache[$object->id]->property_object;
				$loop_sequence_map = [];
				foreach($loop_object as $member => $value){
					$loop_sequence_map[] = $kit::makeMapLocationObject('property','x',$member,$value);
				}
			}else{
				$loop_sequence_map = $old_sequence_maps[$object->order]['property_object_map'];
			}
			$new_sequence_maps[$key]['property_object_map'] = $loop_sequence_map;
			
			if(!isset($object->order)){
				$loop_object = $object_cache[$object->id]->output_object;
				$loop_sequence_map = [];
                foreach($loop_object as $member => $value){
					$loop_sequence_map[] = $kit::makeMapLocationObject('output','x',$member,$value);
				}
			}else{
				$loop_sequence_map = $old_sequence_maps[$object->order]['output_object_map'];
			}
			$new_sequence_maps[$key]['output_object_map'] = $loop_sequence_map;

		}
        
		$input_map_array = [];
		$property_map_array = [];
		$output_map_array = [];
		foreach( $new_sequence_map as $key => $object ){
			$input_map_array = array_merge($input_map_array, $new_sequence_maps[$key]['input_object_map']);
			$property_map_array = array_merge($property_map_array, $new_sequence_maps[$key]['property_object_map']);
			$output_map_array = array_merge($output_map_array, $new_sequence_maps[$key]['output_object_map']);
		}
        
        foreach( $new_sequence_map as $key => $object ){
			if(!array_key_exists($object->id, $object_cache)){
				$object_cache[$object->id] = new $modeler::$model;;
				$object_cache[$object->id]->exists($object->id,'id');
			}
            
			$loop_object = $object_cache[$object->id]->input_object;
			$loop_sequence_map = [];
			foreach( $loop_object as $member => $value){
                $loop_sequence_map[] = $kit::makeMapLocationObject('input', $key+1, $member, $loop_object->$member);
			}
            $default_sequence_maps[$key]['input_object_map'] = $loop_sequence_map;
			
			$loop_object = $object_cache[$object->id]->property_object;
			$loop_sequence_map = [];
			foreach( $loop_object as $member => $value){
				$loop_sequence_map[] = $kit::makeMapLocationObject('property', $key+1, $member, $loop_object->$member);
			}
			$default_sequence_maps[$key]['property_object_map'] = $loop_sequence_map;

			$loop_object = $object_cache[$object->id]->output_object;
			$loop_sequence_map = [];
			foreach( $loop_object as $member => $value){
				$loop_sequence_map[] = $kit::makeMapLocationObject('output', $key+1, $member, $loop_object->$member);
			}
			$default_sequence_maps[$key]['output_object_map'] = $loop_sequence_map;
		}
        
        
		$input_map_defaults_array = [];
		$property_map_defaults_array = [];
		$output_map_defaults_array = [];
		foreach( $new_sequence_map as $key => $object ){
			$input_map_defaults_array = array_merge($input_map_defaults_array, $default_sequence_maps[$key]['input_object_map']);
			$property_map_defaults_array = array_merge($property_map_defaults_array, $default_sequence_maps[$key]['property_object_map']);
			$output_map_defaults_array = array_merge($output_map_defaults_array, $default_sequence_maps[$key]['output_object_map']);
		}
        
        
        if($removeKey > 0){
            foreach( $input_map_array as $key => $object ){
                if( $object->Key === $removeKey ){
                    $input_map_array[$key] = $input_map_defaults_array[$key];
                }
            }
            foreach( $property_map_array as $key => $object ){
                if( $object->Key === $removeKey ){
                    $property_map_array[$key] = $property_map_defaults_array[$key];
                }
            }
            foreach( $output_map_array as $key => $object ){
                if( $object->Key === $removeKey ){
                    $output_map_array[$key] = $output_map_defaults_array[$key];
                }
            }
        }
               
        foreach( $input_map_array as $key => $object ){
            if( $object->Key === 0 || $object->Key == $removeKey ){continue;}
            $object->Key = (array_key_exists($object->Key, $new_keys)) ? $new_keys[$object->Key] : $object->Key;
            $input_map_array[$key] = $object;
        }
        foreach( $property_map_array as $key => $object ){
            if( $object->Key === 0 || $object->Key == $removeKey ){continue;}
            $object->Key = (array_key_exists($object->Key, $new_keys)) ? $new_keys[$object->Key] : $object->Key;
            $property_map_array[$key] = $object;
        }
        foreach( $output_map_array as $key => $object ){
            if( $object->Key === 0 || $object->Key == $removeKey ){continue;}
            $object->Key = (array_key_exists($object->Key, $new_keys)) ? $new_keys[$object->Key] : $object->Key;
            $output_map_array[$key] = $object;
        }
        
        if($addKey > 0){
            foreach( $input_map_array as $key => $object ){
                if( $object->Key === 'x' ){
                    $object->Key = $addKey;
                    $input_map_array[$key] = $object;
                }
            }
            foreach( $property_map_array as $key => $object ){
                 if( $object->Key === 'x' ){
                    $object->Key = $addKey;
                    $property_map_array[$key] = $object;
                }
            }
            foreach( $output_map_array as $key => $object ){
                if( $object->Key === 'x' ){
                    $object->Key = $addKey;
                    $output_map_array[$key] = $object;
                }
            }
        }
        
        foreach( $input_map_array as $key => $object ){
            if( $object->Key === 0 ){continue;}
			if( $object->Key > $input_map_defaults_array[$key]->Key ){
				$input_map_array[$key] = $input_map_defaults_array[$key];
			}
		}
        
		foreach( $property_map_array as $key => $object ){
            if( $object->Key === 0 ){continue;}
			if( $object->Key > $property_map_defaults_array[$key]->Key ){
				$property_map_array[$key] = $property_map_defaults_array[$key];
			}
		}
        
		foreach( $output_map_array as $key => $object ){
            if( $object->Key === 0 ){continue;}
			if( $object->Key > $output_map_defaults_array[$key]->Key ){
				$output_map_array[$key] = $output_map_defaults_array[$key];
			}
		}
		
        
		$loop_array = [];
		foreach( $input_map_array as $object ){
			$loop_array[] = $object;
		}
		$input_map_array = $loop_array;
		
		$loop_array = [];
		foreach( $property_map_array as $object ){
			$loop_array[] = $object;
		}
		$property_map_array = $loop_array;
		
		$loop_array = [];
		foreach( $output_map_array as $object ){
			$loop_array[] = $object;
		}
		$output_map_array = $loop_array;
		
		$input_map_array = array_merge([$root_input_map_object],$input_map_array);
		$property_map_array = array_merge([$root_property_map_object],$property_map_array);
		$output_map_array = array_merge([$root_output_map_object],$output_map_array);
        
        $modeler::model()->sequence = $new_sequence;
		$modeler::model()->input_object_map = $input_map_array;
		$modeler::model()->property_object_map = $property_map_array;
		$modeler::model()->output_object_map = $output_map_array;
		$modeler::model()->save();

		unset($object_cache);
		
		return self::maintenance();
        
	}  
    
}