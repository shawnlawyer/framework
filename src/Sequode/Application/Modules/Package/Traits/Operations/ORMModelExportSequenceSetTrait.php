<?php

namespace Sequode\Application\Modules\Package\Traits\Operations;

use Sequode\View\Export\PHPClosure;

use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;
use Sequode\Application\Modules\Sequode\Kits\Operations as SequodeOperationsKit;
use Sequode\Application\Modules\Package\Modeler as PackageModeler;

trait ORMModelExportSequenceSetTrait {
    
    public static function source(){
        $used_ids = [];
        $sequence_set_model_ids = array_unique(SequodeModeler::model()->sequence);
        
		$sequode_model = new SequodeModeler::$model;
        foreach($sequence_set_model_ids as $id){
            $used_ids[] = $id;
            $sequode_model->exists($id,'id');
            $used_ids = array_merge($used_ids, SequodeModeler::model()->sequence);
        }
		$sequode_model = new SequodeModeler::$model;
        $models = [];
        $where = [];
        $where[] = ['field'=>'owner_id','operator'=>'!=','value'=>AccountModeler::model()->id];
        $where[] = ['field'=>'shared','operator'=>'=','value'=>'1'];
        $where[] = ['field'=>'palette','operator'=>'=','value'=>'0'];
        $sequode_model->getAll($where,'id,name,detail,usage_type,coding_type,sequence,input_object,property_object,output_object,input_object_detail,property_object_detail,output_object_detail,input_object_map,property_object_map,output_object_map,input_form_object,property_form_object');
        
        $models = $sequode_model->all;
        $where = [];
        $where[] = ['field'=>'owner_id','operator'=>'=','value'=>AccountModeler::model()->id];
        $sequode_model->getAll($where,'id,name,detail,usage_type,coding_type,sequence,input_object,property_object,output_object,input_object_detail,property_object_detail,output_object_detail,input_object_map,property_object_map,output_object_map,input_form_object,property_form_object');
        
        $name_to_id = [];
        foreach($sequode_model->all as $key => $object){
            if(in_array($object->id, $sequence_set_model_ids)){
                $name_to_id[$object->name] = $object->id;
            }
        }
        $models = array_merge($models, $sequode_model->all);
        $model_id_to_key = [];
        foreach($models as $key => $object){
            $model_id_to_key[$object->id] = $key;
        }
        $used_ids = [];
        foreach($models as $key => $item){
            $model = new SequodeModeler::$model($item->id);
            $node = (object) null;
            $node->id = $model->id;
            $node->n = $model->name;
            $node->d = $model->detail;
            $node->if = $model->input_form_object;
            $node->pf = $model->property_form_object;
            
            $node->i = $model->input_object;
            $node->p = $model->property_object;
            $node->o = $model->output_object;
            
            $node->ii = $model->input_object_detail;
            $node->pi = $model->property_object_detail;
            $node->oi = $model->output_object_detail;
            
            if($model->usage_type == 1){
                $node->im = $model->input_object_map;
                $node->pm = $model->property_object_map;
                $node->om = $model->output_object_map;
            }
            if($model->usage_type == 1){
                $node->st = $model->usage_type;
                $node->s = $model->sequence;
                $used_ids = array_merge($used_ids,$node->s);
            }elseif($model->usage_type == 0){
                $node->ct = intval($model->coding_type);
                $node->c = '%START_CLOSURE_REPLACEMENT_HOOK%'.SequodeOperationsCardKit::makeCodeFromNode($node).'%END_CLOSURE_REPLACEMENT_HOOK%';
            }
            $models[$key] = $node;
        }
        $used_ids = array_unique($used_ids);
        $filtered_models = [];
        foreach($models as $key => $model){
            if(in_array($model->id, $used_ids)){
                $filtered_models[] = $model;
                $models[$key] = null;
            }
        }
        unset($models);
        $id_to_key = [];
        foreach($filtered_models as $key => $object){
            $id_to_key[$object->id] = $key;
        }
        
        $_o = '<?php
class ' . PackageModeler::model()->token . ' {
    
    use Sequode\Application\Modules\Package\Traits\Operations\SequenceSetExpressTrait;
    
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