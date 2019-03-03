<?php
namespace Sequode\Application\Modules\Traits\Modeler;

trait ActiveModelTrait {

    public static function model($replace = false){

        static $store;

        if(!is_object($store) || $replace === null){

            $store = new static::$model;

        }elseif($replace !== false){

            $store = $replace;

        }
        // part of me is wondering if i would rather just return this by reference.
        return $store;

    }

    public static function exists($value, $by='id'){

        return static::model(null)->exists($value, $by) ?: false ;

    }

}