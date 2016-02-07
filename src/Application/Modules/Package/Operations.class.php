<?php

namespace Sequode\Application\Modules\Package;

use Sequode\View\Export\PHPClosure;
use Sequode\Model\Module\Registry as ModuleRegistry;

class Operations {
    public static $package = 'Package';
    
    public static function getModel($value = null, $by = null, $owner_id = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $_model = new $modeler::$model;
        switch($by){
            case 'id':
            case 'name':
                break;
            default:
                $by = 'id';
                break;
        }
        if($value != null){
            $where[] = array('field'=>$by,'operator'=>'=','value'=>$value);
        }
        if($owner_id != null){
            $where[] = array('field'=>'owner_id','operator'=>'=','value'=>$owner_id);
        }
        $_model->getAll($where,'id',false,1);
        $id = false;
        foreach($_model->all as $key => $object){
            $id = $object->id;
			break;
        }
        if($id != false){
            $modeler::exists($id,'id');
            return $modeler::model();
        }
        return false;   
	}
    public static function updatePackageSequode($sequode_id, $_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->updateField($sequode_id,'sequode_id');
        return $modeler::model();
    }
    public static function updateName($name, $_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->updateField(str_replace(" ","_",$name),'name');
        return $modeler::model();
    }
    public static function newPackage($owner = 0){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $modeler::model()->create();
        $modeler::exists($modeler::model()->id,'id');
        $modeler::model()->updateField(substr(\Sequode\Application\Modules\Session\Modeler::uniqueHash('package','SQDEPAC'),0,15),'name');
        $modeler::model()->updateField($owner,'owner_id');
        return $modeler::model();
	}
    public static function delete($_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->delete($modeler::model()->id);
        return $modeler::model();
    }
    
	public static function download($_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $used_ids = array();
        $package_sequode_model_ids = array_unique(json_decode(\Sequode\Application\Modules\Sequode\Modeler::model()->sequence));
        
		$sequode_model = new \Sequode\Application\Modules\Sequode\Modeler::$model;
        foreach($package_sequode_model_ids as $id){
            $used_ids[] = $id;
            $sequode_model->exists($id,'id');
            $used_ids = array_merge($used_ids, json_decode(\Sequode\Application\Modules\Sequode\Modeler::model()->sequence));
        }
		$sequode_model = new \Sequode\Application\Modules\Sequode\Modeler::$model;
        $models = array();
        $where = array();
        $where[] = array('field'=>'owner_id','operator'=>'!=','value'=>\Sequode\Application\Modules\Auth\Modeler::model()->id);
        $where[] = array('field'=>'shared','operator'=>'=','value'=>'1');
        $where[] = array('field'=>'palette','operator'=>'=','value'=>'0');
        $sequode_model->getAll($where,'id,name,detail,usage_type,coding_type,sequence,input_object,property_object,output_object,input_object_detail,property_object_detail,output_object_detail,input_object_map,property_object_map,output_object_map,input_form_object,property_form_object');
        
        $models = $sequode_model->all;
        $where = array();
        $where[] = array('field'=>'owner_id','operator'=>'=','value'=>\Sequode\Application\Modules\Auth\Modeler::model()->id);
        $sequode_model->getAll($where,'id,name,detail,usage_type,coding_type,sequence,input_object,property_object,output_object,input_object_detail,property_object_detail,output_object_detail,input_object_map,property_object_map,output_object_map,input_form_object,property_form_object');
        
        $name_to_id = array();
        foreach($sequode_model->all as $key => $object){
            if(in_array($object->id, $package_sequode_model_ids)){
                $name_to_id[$object->name] = $object->id;
            }
        }
        $models = array_merge($models, $sequode_model->all);
        $model_id_to_key = array();
        foreach($models as $key => $object){
            $model_id_to_key[$object->id] = $key;
        }
        $used_ids = array();
        foreach($models as $key => $model){
            $node = (object) null;
            $node->id = intval($model->id);
            $node->n = str_replace(' ','_',$model->name);
            $node->d = json_decode($model->detail);
            $node->if = json_decode($model->input_form_object);
            $node->pf = json_decode($model->property_form_object);
            
            $node->i = json_decode($model->input_object);
            $node->p = json_decode($model->property_object);
            $node->o = json_decode($model->output_object);
            
            $node->ii = json_decode($model->input_object_detail);
            $node->pi = json_decode($model->property_object_detail);
            $node->oi = json_decode($model->output_object_detail);
            
            if($model->usage_type == 1){
                $node->im = json_decode($model->input_object_map);
                $node->pm = json_decode($model->property_object_map);
                $node->om = json_decode($model->output_object_map);
            }
            if($model->usage_type == 1){
                $node->st = json_decode($model->usage_type);
                $node->s = json_decode($model->sequence);
                $used_ids = array_merge($used_ids,$node->s);
            }elseif($model->usage_type == 0){
                $node->ct = intval($model->coding_type);
                $node->c = '%START_CLOSURE_REPLACEMENT_HOOK%'.\Sequode\Application\Modules\Sequode\Kits\Operations::makeCodeFromNode($node).'%END_CLOSURE_REPLACEMENT_HOOK%';
            }
            $models[$key] = $node;
        }
        $used_ids = array_unique($used_ids);
        $filtered_models = array();
        foreach($models as $key => $model){
            if(in_array($model->id, $used_ids)){
                $filtered_models[] = $model;
                $models[$key] = null;
            }
        }
        unset($models);
        $id_to_key = array();
        foreach($filtered_models as $key => $object){
            $id_to_key[$object->id] = $key;
        }
        
        $_o = '<?php
class ' . \Sequode\Application\Modules\Package\Modeler::model()->name . ' {
    public static $name = \'' . \Sequode\Application\Modules\Package\Modeler::model()->name . '\';
    public static $token = \'' . \Sequode\Application\Modules\Package\Modeler::model()->token . '\';
    public static $name_to_id = ' . PHPClosure::export($name_to_id, true) . ';
    public static $id_to_key = ' . PHPClosure::export($id_to_key, true) . ';
    public static $index = ' . $package_sequode_model_ids[0] . ';
    ' . file_get_contents('php/SQDE_PackageExpressor.class.phps',true) . '
    public static function collection(){
        return ' . str_replace('Inp_Obj','i', str_replace('Prop_Obj','p', str_replace('Out_Obj','o', str_replace('\'%START_CLOSURE_REPLACEMENT_HOOK%','function($_s){ ',str_replace('%END_CLOSURE_REPLACEMENT_HOOK%\'',' return; }',PHPClosure::export($filtered_models, true)))))) . ';
    }
    public static function exists($value, $by=\'id\'){
        if($by == \'name\'){
            return (isset(static::$name_to_id[$value])) ? static::$id_to_key[static::$name_to_id[$value]] : false;
        }else{ 
            return (isset(static::$id_to_key[intval($value)])) ? static::$id_to_key[intval($value)] : false;
        }
    }
    
    public static function node($value, $by = null){
        switch($by){
            case \'id\':
            case \'name\':
                break;
            default:
                $by = \'id\';
                break;
        }
        $key = self::exists($value,$by);
        
        if($key !== false){
            return self::collection()[$key];
        }
        return false;   
	}
}
';
        return $_o;
    }
}