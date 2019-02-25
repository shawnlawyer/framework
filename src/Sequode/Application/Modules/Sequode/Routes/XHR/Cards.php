<?php

namespace Sequode\Application\Modules\Sequode\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

use Sequode\Application\Modules\Sequode\Module;

use Sequode\Component\Card\Traits\CardsTrait;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

class Cards {

    use CardsTrait;

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
        && AccountAuthority::canView()
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