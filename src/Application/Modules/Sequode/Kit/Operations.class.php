<?php

namespace Sequode\Application\Modules\Sequode\Kit;

class Operations{
	public static function uniqueHash($seed='',$prefix='SQDE'){
		$time = explode(' ', microtime());
        $time = $time[0] + $time[1];
		return $prefix.md5($time.$seed);
	}
    public static function makeSequodeFromModel($sequode_model = null, $add_php=true){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return self::makeCodeFromNode($sequode_model, $add_php);
    }
    public static function makeCodeFromNode($node, $add_php=false){
        if($node->ct == 1){
            return self::makeFunction($node, $add_php);
        }
    }
    public static function makeFunction($node, $add_php=false){
            $input_object = (object) null;
            $input_members = array();
            $parameters = $node->i;
            foreach($parameters as $member => $value){
                $input_object->$member = null;
                $input_members[] = '$_s->i->'.$member;
                
            }
            $code = array();
            $code[] = '$_s->o->Success = true;';
            $code[] = 'if($_s->p->Run_Process === true || intval($_s->p->Run_Process) == 1){';
            $code[] = '    try{';
            $output_object = $node->o;
            if(count(get_object_vars($output_object)) == 2){
                foreach($output_object as $member => $value){
                    if($member != 'Success'){
                        $code[] = '    $_s->o->'.$member.' = '.$node->n.'('.implode(',',$input_members).');';
                        $code[] = '        if($_s->o->'.$member.' === false){';
                        $code[] = '            $_s->o->Success = false;';
                        $code[] = '        }';
                    }
                }
            }else{
                if($node->n == 'echo'){
                    $code[] = '    '.$node->n.' '.implode('.',$input_members).';';
                }else{
                  $code[] = '    '.$node->n.'('.implode(',',$input_members).');';
                }
            }
            $code[] = '    }catch(Exception $e){';
            $code[] = '        $_s->o->Success = false;';
            $code[] = '    }';
            $code[] = '}';
        
        return str_replace('    ','',implode(' ', $code));
    }
    public static function makeStaticMethod($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return self::makeFunction($sequode_model);
    }
    public static function makeSequodeProcessDescriptionNode($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
    
        $node = (object) null;
        $node->id = $sequode_model->id;
        $node->n = $sequode_model->name;
        $node->u = intval($sequode_model->usage_type);
        $node->i = array();
        $node->p = array();
        $node->o = array();
        
        switch($sequode_model->usage_type){
            case 0:
                $node = self::addOnForSequodeCodeNode($node,$sequode_model);
                break;
            case 1:
                $node->s = json_decode($sequode_model->sequence);
                $node->m = array();
                $node->grid_areas = json_decode($sequode_model->grid_areas);
                $node = self::addOnForSequodeSequenceNode($node,$sequode_model);
                break;
        }
        return $node;
    }
    public static function addOnForSequodeCodeNode($node, $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
            
        if(!empty($sequode_model->input_object)){
            foreach(json_decode($sequode_model->input_object) as $loop_member => $loop_value){
                $object = (object) null;
                $object->n = $loop_member;
                $node->i[] = $object;
            }
        }
        if(!empty($sequode_model->property_object)){
            foreach(json_decode($sequode_model->property_object) as $loop_member => $loop_value){
                $object = (object) null;
                $object->n = $loop_member;
                $node->p[] = $object;
            }
        }
        if(!empty($sequode_model->output_object)){
            foreach(json_decode($sequode_model->output_object) as $loop_member => $loop_value){
                $object = (object) null;
                $object->n = $loop_member;
                $node->o[] = $object;
            }
        }
        return $node;
    }
    public static function makeDefaultProcessObjectDetailMember($member){
        switch($member){
            case 'Run_Process':
                return self::makeProcessObjectDetailMember('property','bool' ,'Run Process', false ,'checkboxSwitch' ,true );
            case 'Success':
                return self::makeProcessObjectDetailMember('output','bool' ,'Success', false );
        }
    }
    public static function makeProcessObjectDetailMember($object_type, $type, $display_name, $default_value ,$default_component = 'str', $required = true ){
        $detail_member = (object) null;
        switch($object_type){
            case 'input':
            case 'property':
                $detail_member->type = $type;
                $detail_member->display_name = $display_name;
                $detail_member->required = $required;
                $detail_member->default_value = $default_value;
                $detail_member->default_component = $default_component;
                break;
            case 'output':
                $detail_member->type = $type;
                $detail_member->display_name = $display_name;
                $detail_member->default_value = $default_value;
                break;
        }
        return $detail_member;
    }
    public static function addOnForSequodeSequenceNode($node, $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        
        $input_nodes = array();
        $output_nodes = array();
        $property_nodes = array();
        
        $internal_input_key = 0;
        $internal_output_key = 0;
        $internal_property_key = 0;
        
        $input_object = json_decode($sequode_model->input_object);
        if($input_object != (object) null){
            foreach($input_object as $loop_member => $loop_value){
                $input_nodes[md5('Inp_Obj' .'0'. $loop_member)] = intval(count($input_nodes));
                $object = (object) null;
                $object->n = $loop_member;
                $node->i[] = $object;
            }
        }
        $input_object_map = json_decode($sequode_model->input_object_map);
        $property_object = json_decode(\Sequode\Application\Modules\Sequode\Modeler::model()->property_object);
        if($property_object != (object) null){
            foreach($property_object as $loop_member => $loop_value){
                $property_nodes[md5('Prop_Obj' .'0'. $loop_member)] = intval(count($property_nodes));
                $object = (object) null;
                $object->n = $loop_member;
                $node->p[] = $object;
            }
        }
        $property_object_map = json_decode($sequode_model->property_object_map);
        $sequence = json_decode($sequode_model->sequence);
        $model_object_cache = array();
        $object_cache = array();
        foreach($sequence as $loop_sequence_key => $loop_model_id){
            if(!array_key_exists($loop_model_id, $model_object_cache)){
                $model_object_cache[$loop_model_id] = new \Sequode\Application\Modules\Sequode\Modeler::$model;
                $model_object_cache[$loop_model_id]->exists($loop_model_id,'id');
                $object_cache[$loop_model_id] = (object) null;
                $object_cache[$loop_model_id]->input_object = json_decode($model_object_cache[$loop_model_id]->input_object);
                $object_cache[$loop_model_id]->property_object = json_decode($model_object_cache[$loop_model_id]->property_object);
                $object_cache[$loop_model_id]->output_object = json_decode($model_object_cache[$loop_model_id]->output_object);
            }
            $loop_input_object = $object_cache[$loop_model_id]->input_object;
            $loop_property_object = $object_cache[$loop_model_id]->property_object;
            $loop_output_object = $object_cache[$loop_model_id]->output_object;
            
            $sub_node = (object) null;
            $sub_node->i = array();
            $sub_node->p = array();
            $sub_node->o = array();
            if($loop_input_object != (object) null){
                
                foreach($loop_input_object as $loop_member => $loop_value){
                    $internal_input_key++;
                    $object = (object) null;
                    $location_object = $input_object_map[$internal_input_key];
                    if($location_object->Stack == 'Out_Obj'){
                        $object->stack = 'o';
                        $object->srcKey = $output_nodes[md5('Out_Obj'.$location_object->Key.$location_object->Member)];
                    }else if($location_object->Key == 0 && $location_object->Stack == 'Inp_Obj'){
                        $object->stack = 'i';
                        $object->srcKey = $input_nodes[md5('Inp_Obj' . '0' . $location_object->Member)];
                    }else{
                        $object->stack = 'i';
                        $object->srcKey = '-1';
                    }
                    $input_nodes[md5('Inp_Obj' . $internal_input_key . $loop_member)] = count($input_nodes);
                    $object->lead = $internal_input_key;
                    $sub_node->i[] = $object;
                                        
                }
            }
            if($loop_property_object != (object) null){
                
                foreach($loop_property_object as $loop_member => $loop_value){
                    $internal_property_key++;
                    $object = (object) null;
                    $location_object = $property_object_map[$internal_property_key];
                    if($location_object->Stack == 'Out_Obj'){
                        $object->stack = 'o';
                        $object->srcKey = $output_nodes[md5('Out_Obj'.$location_object->Key.$location_object->Member)];
                    }else if($location_object->Key == 0 && $location_object->Stack == 'Prop_Obj'){
                        $object->stack = 'p';
                        $object->srcKey = $property_nodes[md5('Prop_Obj' . '0' . $location_object->Member)];
                    }else{
                        $object->stack = 'p';
                        $object->srcKey = '-1';
                    }
                    $property_nodes[md5('Prop_Obj' . $internal_property_key . $loop_member)] = count($property_nodes);
                    $object->lead = $internal_property_key;
                    $sub_node->p[] = $object;
                                        
                }
            }
            if($loop_output_object != (object) null){
                foreach(json_decode(json_encode($loop_output_object)) as $loop_member => $loop_value){
                    $internal_output_key++;
                    $output_nodes[md5('Out_Obj'.($loop_sequence_key + 1).$loop_member)] = intval(count($output_nodes));	
                    $object = (object) null;
                    $object->stack = 'o';
                    $object->lead = $internal_output_key;
                    $sub_node->o[] = $object;
                }
            }
            $node->m[] = $sub_node;
        }
        $output_object = json_decode(\Sequode\Application\Modules\Sequode\Modeler::model()->output_object);
        if($output_object != (object) null){
            $output_object_map = json_decode($sequode_model->output_object_map);
            $default_output_object_map = json_decode($sequode_model->default_output_object_map);
            $output_source_keys = array();
            foreach($output_object_map as $loop_key => $loop_object){
                if(intval($loop_object->Key) != 0 || (intval($loop_object->Key) == 0 && $loop_object->Member == 'Root')){
                    continue;
                }
                $output_source_keys[$loop_object->Member] = $output_nodes[md5('Out_Obj'.$default_output_object_map[$loop_key]->Key.$default_output_object_map[$loop_key]->Member)];
            }
            foreach($output_object as $loop_member => $loop_value){
                $object = (object) null;
                $object->n = $loop_member;
                $object->srcKey = $output_source_keys[$loop_member];
                $node->o[] = $object;
            }
        }
        return $node;
    }
	public static function makeDefaultProcessObject($type){
        $_o = (object) null;
        switch($type){
            case 'property':
                $_o->Run_Process = true;
                break;
            case 'output':
                //$o->Success = true;
                break;
            default:
                break;
        }
		return $_o;
	}
    public static function makeProcessObject($type, $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        $new_object = self::makeDefaultProcessObject($type);
        switch($type){
            case 'input':
                $stack = 'Inp_Obj';
                $type_object = 'input_object';
                $type_map = 'input_object_map';
                break;
            case 'property':
                $stack = 'Prop_Obj';
                $type_object = 'property_object';
                $type_map = 'property_object_map';
                break;
            case 'output':
                $stack = 'Out_Obj';
                $type_object = 'output_object';
                $type_map = 'output_object_map';
                break;
        }
		if($sequode_model->$type_map == '[]'){return $new_object;}
		$map = json_decode($sequode_model->$type_map);
        if(count($map) < 1){return $new_object;}
		foreach($map as $key => $object){
			if($object->Key == 0  && $object->Member != 'Root' && $object->Stack == $stack){
				$member_name = $object->Member;
				$new_object->$member_name = null;
			}
		}
		return $new_object;
	}
    public static function makeDefaultProcessInstanceObject( $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
		return json_decode('{"Request": "'.$sequode_model->name.'", "Parameters":{}}');
	}
    public static function makeProcessInstanceObject( $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
		return json_decode('{"Request": "'.$sequode_model->name.'", "Parameters":"'.$sequode_model->input_object.'"}');
	}
    public static function removeKeys($keyed_array){
        $new_array = array();
        foreach((array)$keyed_array as $value){
            $new_array[] = $value;
        }
        return $new_array;
    }
    public static function updateFormObjectMembers($type, $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        switch($type){
            case 'input':
                $type_object = 'input_object';
                $type_form = 'input_form_object';
                break;
            case 'property':
                $type_object = 'property_object';
                $type_form = 'property_form_object';
                break;
            default:
                return false;
        }
		$object = json_decode($sequode_model->$type_object);
        if(count($object) < 1){return (object) null;}
		$form_object = json_decode($sequode_model->$type_form);
        foreach($object as $loop_member => $loop_value){
            //this is temporary till all sequodes can have maintenance run. 
            if($type == 'property' && $loop_member == 'Run_Process'){
                $form_object->$loop_member = json_decode('{"Component":"checkboxSwitch","Label":"Run Process","On_Value":"true","On_Text":"On","Off_Value":"false","Off_Text":"Off","Value":"false"}');
            }else{
             
                if(!isset($form_object->$loop_member) || $form_object->$loop_member == null){
                    if($type == 'property' && $loop_member == 'Run_Process'){
                        $form_object->$loop_member = json_decode('{"Component":"checkboxSwitch","Label":"Run Process","On_Value":"true","On_Text":"On","Off_Value":"false","Off_Text":"Off","Value":"false"}');
                    }else{
                        $component = new \Sequode\Application\Models\ORM\Components;
                        $component->exists('str','name');
                        $form_object->$loop_member = json_decode($component->component_object);
                        $form_object->$loop_member->Label = $loop_member;
                    }
                }   
            }
        }
        return $form_object;
    }
    public static function updateProcessObjectDetails($type,  $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        $sequence = json_decode($sequode_model->sequence);
        switch($type){
            case 'input':
				$type_stack = 'Inp_Obj';
                $type_object = 'input_object';
                $type_detail = 'input_object_detail';
                $type_map = 'input_object_map';
                break;
            case 'property':
				$type_stack = 'Prop_Obj';
                $type_object = 'property_object';
                $type_detail = 'property_object_detail';
                $type_map = 'property_object_map';
                break;
            case 'output':
				$type_stack = 'Out_Obj';
                $type_object = 'output_object';
                $type_detail = 'output_object_detail';
                $type_map = 'output_object_map';
                break;
        }
		$object = json_decode($sequode_model->$type_object);
		$detail = json_decode($sequode_model->$type_detail);
        $default_map = self::makeDefaultSequenceObjectMap($type,$sequode_model);
        $map = json_decode($sequode_model->$type_map);
        if(!is_object($detail)){
            $detail = (object) null;
        }
        if($type == 'property'  && !isset($detail->Run_Process)){
            $detail->Run_Process = self::makeDefaultProcessObjectDetailMember('Run_Process');
        }
        $add_members = array();
        foreach($object as $loop_member => $loop_value){
            if(!isset($detail->$loop_member) || $detail->$loop_member == null){
                $add_members[$loop_member] = null;
            }
        }
        if(
		count($add_members) == 0
		|| ($type == 'property' && count($add_members) == 1 && isset($detail->Run_Process))
		){
            return $detail;
        }
		foreach($map as $map_key => $location_object){
			if(
			!($location_object->Key != 0 || $location_object->Stack != $type_stack)
			|| !isset($add_members[$location_object->Member])
			|| !($location_object->Key == 0 && $location_object->Member != 'Root')
			){
				$add_members[$location_object->Member] = $map_key;
			}
		}
        $object_cache = array();
		foreach( $add_members as $add_member => $value ){
			$external_member = $map[$value]->Member;
			$internal_member = $default_map[$value]->Member;
            $internal_id = $sequence[intval($default_map[$value]->Key)-1];
			if(!array_key_exists($internal_id, $object_cache)){
				$object_cache[$internal_id] = new \Sequode\Application\Modules\Sequode\Modeler::$model;
				$object_cache[$internal_id]->exists($internal_id,'id');
			}
			$loop_object = $object_cache[$internal_id];
			$loop_detail = json_decode($loop_object->$type_detail);
			$detail->$external_member = $loop_detail->$internal_member;
			$detail->$external_member->display_name = $add_member;
		}
        return $detail;
    }
    public static function pruneProcessObjectDetails($type, $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        $new_object = (object) null;
		switch($type){
            case 'input':
                $type_object = 'input_object';
                $type_detail = 'input_object_detail';
                break;
            case 'property':
                $type_object = 'property_object';
                $type_detail = 'property_object_detail';
                $new_object->Run_Process = self::makeDefaultProcessObjectDetailMember('Run_Process');
                break;
            case 'output':
                $type_object = 'output_object';
                $type_detail = 'output_object_detail';
                $new_object->Success = self::makeDefaultProcessObjectDetailMember('Success');
                break;
        }
		$object = json_decode(\Sequode\Application\Modules\Sequode\Modeler::model()->$type_object);
		$object_detail = json_decode(\Sequode\Application\Modules\Sequode\Modeler::model()->$type_detail);
        foreach($object as $loop_member => $loop_value){
            $new_object->$loop_member = $object_detail->$loop_member;
		}
		return $new_object;
	}
    public static function pruneFormObject($type, $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        switch($type){
            case 'input':
                $type_object = 'input_object';
                $type_form = 'input_form_object';
                break;
            case 'property':
                $type_object = 'property_object';
                $type_form = 'property_form_object';
                break;
            default:
                return false;
        }
		$object = json_decode($sequode_model->$type_object);
        if(count($object) < 1){return (object) null;}
		$form_object = json_decode($sequode_model->$type_form);
        $new_object = (object) null;
        foreach($object as $loop_member => $loop_value){
            $new_object->$loop_member = (isset($form_object->$loop_member)) ? $form_object->$loop_member : null;
		}
		return $new_object;
	}
    public static function makeDefaultSequenceObjectMap($type, $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        
        switch($type){
            case 'input':
                $type_object = 'input_object';
                break;
            case 'property':
                $type_object = 'property_object';
                break;
            case 'output':
                $type_object = 'output_object';
                break;
            default:
                return false;
        }
        $object_cache = array();
        $map = array();
		$map[] = self::makeMapLocationObject($type,0,'Root','');
        $sequence = json_decode($sequode_model->sequence);
        foreach( $sequence as $key => $id ){
			if(!array_key_exists($id, $object_cache)){
				$object_cache[$id] = new \Sequode\Application\Modules\Sequode\Modeler::$model;
				$object_cache[$id]->exists($id,'id');
			}
			$loop_object = json_decode($object_cache[$id]->$type_object);
			foreach( $loop_object as $member => $value){
                $map[] = self::makeMapLocationObject($type,$key+1,$member,$loop_object->$member);
			}
		}
        return $map;
    }
    public static function makeUniqueMemberName($member, $map, $increment=1){
        $member_array = explode('_',$member);
        if(is_numeric(end($member_array))){
            array_pop($member_array);
        }
        $member = implode('_',$member_array);
        $member_name = ($increment == 1) ? $member : $member.'_'.$increment;
        foreach($map as $key => $object){
            if($object->Key == 0 && $object->Member == $member_name){
                $increment++;
                $member_name = self::makeUniqueMemberName($member,$map,$increment);
                break;
            }
        }
        return $member_name;
    }
    public static function makeMapLocationObject($type, $key, $member, $value){
        $object = (object) null;
        switch($type){
            case 'input':
                $object->Stack = 'Inp_Obj';
                break;
            case 'property':
                $object->Stack = 'Prop_Obj';
                break;
            case 'output':
                $object->Stack = 'Out_Obj';
                break;
        }
        $object->Key = $key;
        $object->Member = $member;
        $object->Value = $value;
        return $object;
    }
	public static function makeUpdateSequenceInputMap($sequence){
        $map = array(); 
		foreach($sequence as $key => $value){
			$loop_object = (object) NULL;
			$loop_object->id = $value;
			$loop_object->order = $key;
			$map[] = $loop_object;
		}
		return $map;
	}
	public static function addToUpdateSequenceInputMap($map, $id, $position){
		$new_map = array();
		if(count($map) == 0){
			$new_object = (object) NULL;
			$new_object->id = $id;
			$new_map[] = $new_object;
		}else{
			if(isset($map[$position])){
				foreach($map as $key => $object){
					if(intval($key) == intval($position)){
						$new_object = (object) NULL;
						$new_object->id = $id;
						$new_map[] = $new_object;
					}
					$new_map[] = $object;
				}
			}else{
				$new_map = $map;
				$new_object = (object) NULL;
				$new_object->id = $id;
				$new_map[] = $new_object;
			}
		}
		return $new_map;
	}
	public static function removeFromUpdateSequenceInputMap($map, $position){
		if($position == 0){
			array_shift($map);
		}elseif($position == -1){
			array_reverse($map);
			array_shift($map);
			array_reverse($map);
		}else{
			$new_map = array();
			foreach($map as $key => $object){
				if(intval($key) == intval($position)){
					continue;
				}			
				$new_map[] = $object;
			}
			$map = $new_map;
		}
		return $map;
	}
	public static function reorderUpdateSequenceInputMap($map, $from_position, $to_position){
		if($from_position == $to_position){return $map;}
		$new_map = array();
		foreach($map as $key => $object){
			if(intval($key) == $from_position){	continue; }
			if(intval($key) == $to_position){
				if($from_position < $to_position){
					$new_map[] = $object;
					$new_map[] = $map[$from_position];
				}else{
					$new_map[] = $map[$from_position];
					$new_map[] = $object;
				}
			}else{
				$new_map[] = $object;
			}
		}
		return $new_map;
	}
    /*

    public static function makeCodedProcessParameterName($name){
        $images = array('Col' => 'Color','C' => 'Character','Cx' => 'X','Cy' => 'Y','S' => 'Start_Angle','E' => 'End_Angle','SrcX' => 'Source_X','SrcY' => 'Source_Y','Matrix3x3' => 'Matrix_3_By_3');
        $arrays = array('Arg' => 'Array','Stack' => 'Array','Input' => 'Array','Arg_1' => 'Array_1','Arg_2' => 'Array_2');
        $minute = array('Min' => 'Minute');
        $minimum = array('Min' => 'Minimum');
        $general = array('Arr' => 'Array','Arr1' => 'Array_1','Arr2' => 'Array_2','Len' => 'Length','Len' => 'Length','H' => 'Height','W' => 'Width','Str' => 'String','Jd' => 'Jewish_Date','Params' => 'Parameters','Dir_Handle' => 'Directory_Handler','Const_Name' => 'Constant_Name','Var' => 'Variable','Ch' => 'Curl_Handler','Mh' => 'Multi_Handler','Addr' => 'Address','Fp' => 'File_Handler','Pasv' => 'Passave','Startpos' => 'Start_Position','Src' => 'Source','Dest' => 'Destination','Cmd' => 'Command','Varname' => 'Variable_Name','Sec' => 'Seconds','Mon' => 'Month','Imagetype' => 'Image_Type','Im' => 'Image','Im1' => 'Image_1','Im2' => 'Image_2','Src_Im' => 'Source_Image','Dst_Im' => 'Destination_Image','Dst_X' => 'Destination_X','Dst_Y' => 'Destination_Y','Dst_W' => 'Destination_Width','Dst_H' => 'Destination_Height','Src_X' => 'Source_X','Src_Y' => 'Source_Y','Src_W' => 'Source_Width','Src_H' => 'Source_Height','Pct' => 'Percent','Val' => 'Value','Juliandaycount' => 'Julian_Day_Count','Jday' => 'Julian_Day','Str' => 'String','Str1' => 'String_1','Str2' => 'String_2','Pathname' => 'Path','Filename' => 'File_Name','Hostname' => 'Host_Name','Char_List' => 'Character_List','Main_Str' => 'Main_String','Microseconds' => 'Micro_Seconds','Maxlen' => 'Max_Length','Num_Req' => 'Picked_Size','Arg1' => 'Arg_1','Arg2' => 'Arg_2','F_Org' => 'Original_File_Path','F_Dest' => 'New_File_Path','D_Height' => 'Height','D_Width' => 'Width','D_Threshold' => 'Threshold','Cost_Ins' => 'Cost_Of_Insert','Cost_Rep' => 'Cost_Of_Replacement','Cost_Del' => 'Cost_Of_Deletion','Descriptorspec' => 'Descriptor_Specifier','Cwd' => 'Currnt_Working_Directory','Env' => 'Enviroment','Localaddress' => 'Local_Address','Errcode' => 'Error_Code','Errstring' => 'Error_String','S1' => 'String_1','S2' => 'String_2','Classname' => 'Class_Name','Newvalue' => 'New_Value','ColorsWanted' => 'Colors_Wanted','DitherFlag' => 'Dither_Flag','Xcoord' => 'X','Ycoord' => 'Y','X1' => 'X_1','X2' => 'X_2','Y1' => 'Y_1','Y2' => 'Y_2','Dst' => 'Destination','Inputgamma' => 'Input_Gamma','Outputgamma' => 'Output_Gamma','Extrainfo' => 'Extra_Information','Num1' => 'Number_1','Num2' => 'Number_2','Arg_Separator' => 'Argument_Separator','Formdata' => 'Form_Data','Algo' => 'Algorithium','Max' => 'Maximum','Extension_Filename' => 'Extension_File_Name','SORT_ASC_Or_SORT_DESC' => 'Sort_Order','SORT_REGULAR_Or_SORT_NUMERIC_Or_SORT_STRING' => 'Sort_Type','Callback_Key_Comp_Func' => 'Callback_Key_Compare_Function','Callback_Key_Compare_Func' => 'Callback_Key_Compare_Function','Callback_Data_Comp_Func' => 'Callback_Data_Compare_Function');

        $name = str_replace(' ','_',ucwords(str_replace('_',' ',$name)));

        if(strpos('image',$function) === 0 && array_key_exists($name,$images)){ return $images[$name]; }
        if(strpos('array',$function) !== false && array_key_exists($name,$arrays)){ return $arrays[$name]; }
        if(array_key_exists($name,$general)){ return $general[$name]; }
        if(strpos('time',$function) !== false){ if(array_key_exists($name,$minute)){ return $minute[$name]; }
        }else{ if(array_key_exists($name,$minimum)){ return $minimum[$name]; } }
        return $name;
    }
    
    */
	public static function getGridAreasKeyFromPosition($position, $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
		$sequence = json_decode($sequode_model->sequence);
		$grid_areas = json_decode($sequode_model->grid_areas);
		if($position == count($sequence)){
			return count($grid_areas) - 1;
		}
		$count = 0;
		$key = 0;
		foreach($grid_areas as $key => $grid_area){
			$count += $grid_area->count;
			if($count > $position){
				return $key;
			}
		}
    }
	public static function getGridAreaPosition($position, $grid_areas){
        $i=0;
		foreach($grid_areas as $grid_area){
			for($j=0; $j < $grid_area->count; $j++){
				if($position  == $i){
					return $j;
				}
                $i++;
			}
		}
		return $grid_areas[count($grid_areas) - 1]->count;
    }
	public static function getSequencePosition($position, $sequence, $max_position_modifier = 0){
		if(count($sequence) == 0){ return 0;}
		$minimum_position = 0;	
		$max_position = ($max_position_modifier == 0) ? count($sequence) - 1 : count($sequence);
		
		if($position < $minimum_position){
			return $minimum_position;
		}elseif($position > $max_position){
			return $max_position;
		}
		return $position;
    }
	public static function reduceGridAreaCount($grid_areas, $key){
		$grid_areas[$key]->count--;
		return $grid_areas;
	}
	public static function increaseGridAreaCount($grid_areas, $key){
		$grid_areas[$key]->count++;
		return $grid_areas;
	}
	public static function removeZeroCountGridAreas($grid_areas){
		if(count($grid_areas) == 1){return $grid_areas;}
		$new_grid_areas = array();
		foreach($grid_areas as $grid_area){
			if($grid_area->count > 0){
				$new_grid_areas[] = $grid_area;
			}
		}
		return $new_grid_areas;
	}
	public static function addToGridArea($position, $grid_areas, $grid_modifier, $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
		$grid_area_key = self::getGridAreasKeyFromPosition($position, $sequode_model);
		$grid_areas = self::increaseGridAreaCount($grid_areas, $grid_area_key);
		return $grid_areas;
    }
	public static function moveFromGridAreaToGridArea($from_position, $to_position, $grid_areas, $sequode_model = null ){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
		$grid_area_key_from = self::getGridAreasKeyFromPosition($from_position, $sequode_model);
		$grid_area_key_to = self::getGridAreasKeyFromPosition($to_position, $sequode_model);

		if($grid_area_key_from == $grid_area_key_to){
			return $grid_areas;
		}
		$grid_areas = self::reduceGridAreaCount($grid_areas, $grid_area_key_from);
		$grid_areas = self::increaseGridAreaCount($grid_areas, $grid_area_key_to);
		$grid_areas = self::removeZeroCountGridAreas($grid_areas);
		return $grid_areas;
    }
	public static function tuneGridAreaPosition($position, $grid_areas, $position_tuner, $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
		$grid_area_key = self::getGridAreasKeyFromPosition($position, $sequode_model);
		$grid_area_position = self::getGridAreaPosition($position, $grid_areas);
		
		switch($position_tuner){
			case 0:
				if($position != 0 && $grid_area_position == ($grid_areas[$grid_area_key]->count - 1) && isset($grid_areas[$grid_area_key + 1])){
					$grid_areas = self::reduceGridAreaCount($grid_areas, $grid_area_key);
					$grid_areas = self::increaseGridAreaCount($grid_areas, ($grid_area_key + 1));
				}
				break;
			case 1:
				if($grid_area_key > 0 && $grid_area_position == 0){
					$grid_areas = self::reduceGridAreaCount($grid_areas,$grid_area_key);
					$grid_areas = self::increaseGridAreaCount($grid_areas,($grid_area_key - 1));
				}
				break;
		}
		
		$grid_areas = self::removeZeroCountGridAreas($grid_areas);
		return $grid_areas;
    }
	public static function modifyGridAreas($position, $grid_areas, $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
		$grid_area_key = self::getGridAreasKeyFromPosition($position, $sequode_model);
		$grid_area_position = self::getGridAreaPosition($position, $grid_areas);
        
        if($grid_area_position == 0 && $grid_areas[$grid_area_key]->count > 1){
            //create grid area to the left
            $modify = 'prepend';
        }elseif($grid_area_position != 0 && $grid_area_position == ($grid_areas[$grid_area_key]->count - 1)){
            $modify = 'append';
        }elseif($grid_area_position != 0 && $grid_area_position != ($grid_areas[$grid_area_key]->count - 1)){
            $modify = 'split';
        }else{
            return $grid_areas;
        }
        $cell_height = 250;
        $cell_width = 50;
        $grid_area_width_padding = 200;
        $new_grid_areas = array();
        foreach($grid_areas as $key => $grid_area){
            if($grid_area_key == $key ){
                switch($modify){
                    case 'prepend':
                        $original_grid_area = self::defaultGridArea($grid_area->count - 1);
                        $original_grid_area->x = $grid_area->x + ($cell_width + $grid_area_width_padding);
                        $original_grid_area->y = $grid_area->y;
                        $prepend_grid_area = self::defaultGridArea(1);
                        $prepend_grid_area->x = $grid_area->x;
                        $prepend_grid_area->y = $grid_area->y;
                        $new_grid_areas[] = $prepend_grid_area;
                        $new_grid_areas[] = $original_grid_area;
                        break;
                    case 'append':
                        $original_grid_area = self::defaultGridArea($grid_area->count - 1);
                        $original_grid_area->x = $grid_area->x;
                        $original_grid_area->y = $grid_area->y;
                        $append_grid_area = self::defaultGridArea(1);
                        $append_grid_area->x = $grid_area->x + (($cell_width * $original_grid_area->count) + $grid_area_width_padding);
                        $append_grid_area->y = $grid_area->y;
                        $new_grid_areas[] = $original_grid_area;
                        $new_grid_areas[] = $append_grid_area;
                        break;
                    case 'split':
                        $original_grid_area = self::defaultGridArea($grid_area_key + 1);
                        $original_grid_area->x = $grid_area->x;
                        $original_grid_area->y = $grid_area->y;
                        $middle_grid_area = self::defaultGridArea(1);
                        $middle_grid_area->x = $grid_area->x + (($cell_width * $original_grid_area->count) + $grid_area_width_padding);
                        $middle_grid_area->y = $grid_area->y + $cell_height;
                        $end_grid_area = self::defaultGridArea($grid_area->count - $original_grid_area->count - 1);
                        $end_grid_area->x = $middle_grid_area->x + (($cell_width * $middle_grid_area->count) + $grid_area_width_padding);
                        $end_grid_area->y = $original_grid_area->y;
                        $new_grid_areas[] = $original_grid_area;
                        $new_grid_areas[] = $middle_grid_area;
                        $new_grid_areas[] = $end_grid_area;
                        break;
                }
            }else{
                $new_grid_areas[] = $grid_area;
            }
        }
        return self::removeZeroCountGridAreas($new_grid_areas);
    }
    public static function moveGridArea($gridarea_key, $grid_areas, $x = 0, $y = 0, $sequode_model = null){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        if($gridarea_key == 0 || ( $x == 0 && $y == 0 )){return $grid_areas;}
        
        $cell_height = 250;
        $cell_width = 50;
        $x = floor($x/$cell_width)*$cell_width;
        $y = floor($y/$cell_height)*$cell_height;
        $grid_areas[$gridarea_key]->x = $x;
        $grid_areas[$gridarea_key]->y = $y;
        //echo 'console.log(\''. json_encode($grid_areas) .'\'); ';
        return $grid_areas;
    }
	public static function removeFromGridArea($position, $grid_areas, $sequode_model = null ){
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
		$grid_area_key = self::getGridAreasKeyFromPosition($position, $sequode_model);
		$grid_areas = self::reduceGridAreaCount($grid_areas,$grid_area_key);
		$grid_areas = self::removeZeroCountGridAreas($grid_areas);
		if(count($grid_areas) == 0){
			$grid_areas = self::defaultGridAreas();
		}
		return $grid_areas;
    }
	public static function defaultGridArea($count = 0){
		$grid_area = (object) null;
		$grid_area->count = $count;
		$grid_area->x = 0;
		$grid_area->y = 0;
		return $grid_area;
    }
	public static function defaultGridAreas($count=0){
		return array(self::defaultGridArea($count));
    }
	
}


