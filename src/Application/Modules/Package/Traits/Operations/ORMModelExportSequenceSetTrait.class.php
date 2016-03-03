<?php

namespace Sequode\Application\Modules\Package\Traits\Operations;

use Sequode\View\Export\PHPClosure;

trait ORMModelExportSequenceSetTrait {
    
    public static function download(){
        $used_ids = array();
        $sequence_set_model_ids = array_unique(json_decode(\Sequode\Application\Modules\Sequode\Modeler::model()->sequence));
        
		$sequode_model = new \Sequode\Application\Modules\Sequode\Modeler::$model;
        foreach($sequence_set_model_ids as $id){
            $used_ids[] = $id;
            $sequode_model->exists($id,'id');
            $used_ids = array_merge($used_ids, json_decode(\Sequode\Application\Modules\Sequode\Modeler::model()->sequence));
        }
		$sequode_model = new \Sequode\Application\Modules\Sequode\Modeler::$model;
        $models = array();
        $where = array();
        $where[] = array('field'=>'owner_id','operator'=>'!=','value'=>\Sequode\Application\Modules\Account\Modeler::model()->id);
        $where[] = array('field'=>'shared','operator'=>'=','value'=>'1');
        $where[] = array('field'=>'palette','operator'=>'=','value'=>'0');
        $sequode_model->getAll($where,'id,name,detail,usage_type,coding_type,sequence,input_object,property_object,output_object,input_object_detail,property_object_detail,output_object_detail,input_object_map,property_object_map,output_object_map,input_form_object,property_form_object');
        
        $models = $sequode_model->all;
        $where = array();
        $where[] = array('field'=>'owner_id','operator'=>'=','value'=>\Sequode\Application\Modules\Account\Modeler::model()->id);
        $sequode_model->getAll($where,'id,name,detail,usage_type,coding_type,sequence,input_object,property_object,output_object,input_object_detail,property_object_detail,output_object_detail,input_object_map,property_object_map,output_object_map,input_form_object,property_form_object');
        
        $name_to_id = array();
        foreach($sequode_model->all as $key => $object){
            if(in_array($object->id, $sequence_set_model_ids)){
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
    
    use \Sequode\Application\Modules\Package\Traits\Operations\SequenceSetExpressTrait;
    
    public static $name_to_id = ' . PHPClosure::export($name_to_id, true) . ';
    public static $id_to_key = ' . PHPClosure::export($id_to_key, true) . ';
    public static $index = ' . $sequence_set_model_ids  [0] . ';
    public static function models(){
        return ' . str_replace('Inp_Obj','i', str_replace('Prop_Obj','p', str_replace('Out_Obj','o', str_replace('\'%START_CLOSURE_REPLACEMENT_HOOK%','function($_s){ ',str_replace('%END_CLOSURE_REPLACEMENT_HOOK%\'',' return; }',PHPClosure::export($filtered_models, true)))))) . ';
    }
}
';

        return $_o;
        
    }
}