<?php

namespace Sequode\Application\Modules\Package\Traits\Operations;

use Sequode\View\Export\PHPClosure;

use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;
use Sequode\Application\Modules\Sequode\Kits\Operations as SequodeOperationsKit;
use Sequode\Application\Modules\Package\Modeler as PackageModeler;

trait ORMModelExportSequenceSetTrait {

    public static function source(){
        $name_to_id = [];
        foreach(array_unique(SequodeModeler::model()->sequence) as $id){
            $name_to_id[(new SequodeModeler::$model)->exists($id)->name] = $id;
        }
        $model_id_to_key = [];
        foreach($name_to_id as $name => $id){
            $model_id_to_key[$id] = $name;
        }
        $models = [];
        foreach(SequodeModeler::model()->deep_sequence() as $id){

            $model = (new SequodeModeler::$model)->exists($id);
            $node = (object)[];
            $node->id = $model->id;
            $node->n = $model->name;
            $node->d = $model->detail;
            $node->if = $model->input_form_object;
            $node->pf = $model->property_form_object;

            $node->i = $model->input_object;
            $node->p = $model->property_object;
            $node->o =$model->output_object;

            $node->ii = $model->input_object_detail;
            $node->pi = $model->property_object_detail;
            $node->oi = $model->output_object_detail;

            switch($model->usage_type){
                case 0:
                    $node->ct = intval($model->coding_type);
                    $node->c = '%START_CLOSURE_REPLACEMENT_HOOK%'.SequodeOperationsKit::makeCodeFromNode($node).'%END_CLOSURE_REPLACEMENT_HOOK%';
                    break;
                case 1:
                    $node->im = $model->input_object_map;
                    $node->pm = $model->property_object_map;
                    $node->om = $model->output_object_map;
                    $node->st = $model->usage_type;
                    $node->s = $model->sequence;
                    break;
            }
            $models[] = $node;
        }
        $id_to_key = [];
        foreach($models as $key => $object){
            $id_to_key[$object->id] = $key;
        }

        $_o = '<?php
        
use Sequode\Application\Modules\Package\Traits\Operations\SequenceSetExpressTrait;
        
class ' . PackageModeler::model()->token . ' {
    
    use SequenceSetExpressTrait;
    
    public static $name_to_id = ' . PHPClosure::export($name_to_id, true) . ';
    public static $id_to_key = ' . PHPClosure::export($id_to_key, true) . ';
    public static $index = ' . SequodeModeler::model()->sequence[0] . ';
    public static function models(){
        return ' . str_replace('Inp_Obj','i', str_replace('Prop_Obj','p', str_replace('Out_Obj','o', str_replace('\'%START_CLOSURE_REPLACEMENT_HOOK%','function($_s){ ',str_replace('%END_CLOSURE_REPLACEMENT_HOOK%\'',' return; }',PHPClosure::export($models, true)))))) . ';
    }
}
';
        return $_o;

    }
}