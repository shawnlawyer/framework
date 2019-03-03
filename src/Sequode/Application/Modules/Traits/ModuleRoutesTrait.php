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
}