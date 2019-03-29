<?php
namespace Sequode\Application\Modules\Traits\Modeler;

trait ActiveModelTrait {

    public static function model($replace = false){

        static $store;

        if($replace === null || (!is_object($store) && $replace === false)){

            $store = new static::$model;

        }elseif($replace !== false){

            $store = $replace;

        }
        return $store;

    }

    public static function exists($value, $by='id'){

        return static::model(null)->exists($value, $by) ?: false ;

    }

    public static function create($data = []){
        $class = static::$model;
        return static::model($class::create($data)) ?: false ;

    }

}