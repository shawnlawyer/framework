<?php

namespace Sequode\Application\Modules\Traits;

trait ModuleRoutesTrait {

    public static function xhrOperationRoute($operation){

        return 'operations/'.static::model()->context.'/'.$operation;

    }

    public static function xhrCardRoute($card){

        return 'cards/'.static::model()->context.'/'.$card;

    }

    public static function xhrFormRoute($form){

        return 'forms/'.static::model()->context.'/'.$form;

    }

    public static function variables($filter = []){

        $model = static::model();

        $vars = [];

        $vars += ['module' => static::class];

        if(isset($model->modeler)){

            $vars += ['modeler' => $model->modeler];

        }

        if(isset($model->operations)){

            $vars += ['operations' => $model->operations];

        }

        if(isset($model->xhr->operations)){

            $vars += ['xhr_operations' => $model->xhr->operations];

        }

        if(isset($model->xhr->cards)){

            $vars += ['xhr_cards' => $model->xhr->cards];

        }

        if(isset($model->context)){

            $vars += ['context' => $model->context];

        }

        if(isset($model->components->form_inputs)){

            $vars += ['component_form_inputs' => $model->components->form_inputs];

        }

        if(isset($model->components->forms)){

            $vars += ['component_forms' => $model->components->forms];

        }

        if(isset($model->components->cards)){

            $vars += ['component_cards' => $model->components->cards];

        }

        if(isset($model->components->dialogs)){

            $vars += ['component_dialogs' => $model->components->dialogs];

        }

        if(!empty($filter)){

            $filtered_vars = [];

            foreach($filter as $key){

                if(array_key_exists($key, $vars)){

                    $filtered_vars += [$key => $vars[$key]];

                }

            }

            return $filtered_vars;

        }else{

            return $vars;
        }

    }

    public static function collection($collections){

        //return 'forms/'.static::model()->context.'/'.$form;

    }

    public static function context(){

        return static::model()->context;

    }

}