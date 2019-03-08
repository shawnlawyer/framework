<?php

namespace Sequode\Application\Modules\Sequode\Routes\XHR;

use Sequode\Application\Modules\Sequode\Module;
use Sequode\Application\Modules\Traits\Routes\XHR\CardsCardRouteTrait as XHRCardsCardRouteTrait;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

class Cards {

    use XHRCardsCardRouteTrait;

    public static $module = Module::class;

    public static $routes = [
        'componentSettings',
        'details',
        'internalForms',
        'internalPositionForms',
        'chart',
        'sequencer',
        'search',
        'my',
        'favorites',
    ];
    
    public static function componentSettings($type = false, $member=null, $_model_id=0){
       
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && (AccountAuthority::isOwner( $modeler::model() )
        || AccountAuthority::isSystemOwner())
        )){return false;}
        return [$type, $member];
    }

    public static function details($_model_id=0){

        $module = static::$module;
        $modeler = $module::model()->modeler;

        if(!(
        $modeler::exists($_model_id,'id')
        && AccountAuthority::canView( $modeler::model() )
        )){return false;}
    }

    public static function internalForms($_model_id=0){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && (AccountAuthority::isOwner( $modeler::model() )
        || AccountAuthority::isSystemOwner())
        )){return false;}
    }

    public static function internalPositionForms($_model_id=0, $position=0){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && (AccountAuthority::isOwner( $modeler::model() )
        || AccountAuthority::isSystemOwner())
        )){return false;}
        return [$position];
    }

    public static function chart($_model_id=0){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && (AccountAuthority::isOwner( $modeler::model() )
        || AccountAuthority::isSystemOwner())
        )){return false;}
    }

    public static function sequencer($_model_id=0){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && (AccountAuthority::isOwner( $modeler::model() )
        || AccountAuthority::isSystemOwner())
        )){return false;}
    }
}