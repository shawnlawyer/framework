<?php

namespace Sequode\Application\Modules\Package\Traits\Operations;

use Sequode\View\Export\PHPClosure;

use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;
use Sequode\Application\Modules\Package\Modeler as PackageModeler;

trait ORMModelExportSequenceSetTrait {

    public static function source(){

        $package_model = PackageModeler::model();

        $sequode_model = SequodeModeler::model();

        $models = static::nodes($sequode_model->deep_sequence);

        $id_to_key = static::idToKey($models);

        $name_to_id = static::nameToId($sequode_model->sequence);

        $models_translations = [
            "'Stack' => 'Inp_Obj'"  => "'Stack' => 'i'",
            "'Stack' => 'Prop_Obj'" => "'Stack' => 'p'",
            "'Stack' => 'Out_Obj'"  => "'Stack' => 'o'",
            "'%REMOVE_QUOTE_HOOK%"  => '',
            "%REMOVE_QUOTE_HOOK%'"  => '',
        ];

        $class_translations = [
            "%CLASS_NAME%" => $package_model->token,
            "%INDEX%"      => $sequode_model->sequence[0],
            "%NAME_TO_ID%" => PHPClosure::export($name_to_id),
            "%ID_TO_KEY%"  => PHPClosure::export($id_to_key),
            "%MODELS%"     => strtr(PHPClosure::export($models), $models_translations),
        ];

        return strtr(static::classTemplate(), $class_translations);

    }

    public static function nodes($ids){

        $nodes = [];
        foreach ($ids as $id) {

            $model = (new SequodeModeler::$model)->exists($id);
            $node = (object)[];
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

            switch ($model->usage_type) {
                case 0:
                    $node->ct = intval($model->coding_type);
                    $node->c = '%REMOVE_QUOTE_HOOK%' . static::makeCodeFromNode($node) . '%REMOVE_QUOTE_HOOK%';
                    break;
                case 1:
                    $node->im = $model->input_object_map;
                    $node->pm = $model->property_object_map;
                    $node->om = $model->output_object_map;
                    $node->st = $model->usage_type;
                    $node->s = $model->sequence;
                    break;
            }
            $nodes[] = $node;
        }
        return $nodes;
    }

    public static function idToKey($nodes)
    {
        $id_to_key = [];
        foreach ($nodes as $key => $node) {
            $id_to_key[$node->id] = $key;
        }
        return $id_to_key;
    }

    public static function nameToId($ids)
    {
        $name_to_id = [];
        foreach($ids as $id){
            $name_to_id[(new SequodeModeler::$model)->exists($id)->name] = $id;
        }
        return $name_to_id;
    }

    public static function classTemplate()
    {

        $code = [];
        $code[] = '<?php';
        $code[] = 'use Sequode\Application\Modules\Package\Traits\Operations\SequenceSetExpressTrait;';
        $code[] = 'class %CLASS_NAME% {';
        $code[] = '    use SequenceSetExpressTrait;';
        $code[] = '    public static $name_to_id = %NAME_TO_ID%;';
        $code[] = '    public static $id_to_key = %ID_TO_KEY%;';
        $code[] = '    public static $index = %INDEX%;';
        $code[] = '    public static function models(){';
        $code[] = '        return %MODELS%;';
        $code[] = '    }';
        $code[] = '}';

        return implode(PHP_EOL.PHP_EOL, $code);
    }
    public static function makeCodeFromNode($node)
    {
            if ($node->ct == 1) {
                return self::makeFunction($node);
            }
    }
    public static function makeFunction($node){
        $input_members = [];
        $parameters = $node->i;
        foreach($parameters as $member => $value){
            $input_members[] = '$_s->i->'.$member;

        }
        $code = [];
        $code[] = 'function($_s){ ';
        $code[] = '    $_s->o->Success = true;';
        $code[] = '    if($_s->p->Run_Process === true || intval($_s->p->Run_Process) == 1){';
        $code[] = '        try{';
        $output_object = $node->o;
        if(count(get_object_vars($output_object)) == 2){
            foreach($output_object as $member => $value){
                if($member != 'Success'){
                    $code[] = '            $_s->o->'.$member.' = '.$node->n.'('.implode(',',$input_members).');';
                    $code[] = '            if($_s->o->'.$member.' === false){';
                    $code[] = '                $_s->o->Success = false;';
                    $code[] = '            }';
                }
            }
        }else{
            if($node->n == 'echo'){
                $code[] = '        '.$node->n.' '.implode('.',$input_members).';';
            }else{
                $code[] = '        '.$node->n.'('.implode(',',$input_members).');';
            }
        }
        $code[] = '        }catch(Exception $e){';
        $code[] = '            $_s->o->Success = false;';
        $code[] = '        }';
        $code[] = '    }';
        $code[] = '    return;';
        $code[] = '}';

        return str_replace('    ','',implode(' ', $code));
    }
    public static function makeSequodeFromModel($sequode_model = null){
        if($sequode_model == null ){ $sequode_model = SequodeModeler::model(); }
        return self::makeCodeFromNode(static::node($sequode_model->id)[0]);
    }
}