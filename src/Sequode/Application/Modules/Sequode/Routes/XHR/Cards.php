<?php

namespace Sequode\Application\Modules\Sequode\Routes\XHR;

use Sequode\Application\Modules\Sequode\Module;
use Sequode\Application\Modules\Traits\Routes\XHR\CardsCardRouteTrait as XHRCardsCardRouteTrait;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

class Cards {

    use XHRCardsCardRouteTrait;

    const Module = Module::class;

    public static $routes = [
        'componentSettings',
        'details',
        'internalForms',
        'internalPositionForms',
        'chart',
        'sequencer',
        'search',
        'owned',
        'favorites',
    ];

    const Routes = [
        'componentSettings',
        'details',
        'internalForms',
        'internalPositionForms',
        'chart',
        'sequencer',
        'search',
        'owned',
        'favorites',
    ];
    
    public static function componentSettings($type = false, $member=null, $_model_id=0, $dom_id = null){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){return false;}

        return [
            "type" => $type,
            "member" => $member,
            "model" => $modeler::model(),
            "dom_id" => $dom_id
        ];

    }

    public static function details($_model_id=0, $dom_id=null){

        extract((static::Module)::variables());

        if(!(
            $modeler::exists($_model_id,'id')
            && AccountAuthority::canView( $modeler::model() )
        )){return false;}

        return [
            "model" => $modeler::model(),
            "dom_id" => $dom_id
        ];

    }

    public static function internalForms($_model_id=0, $dom_id=null){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){return false;}

        return [
            "model" => $modeler::model(),
            "dom_id" => $dom_id
        ];

    }

    public static function internalPositionForms($_model_id=0, $position=0, $dom_id=null){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){return false;}

        return [
            "position" => $position,
            "model" => $modeler::model(),
            "dom_id" => $dom_id
        ];

    }

    public static function chart($_model_id=0, $dom_id=null){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){return false;}

        return [
            "model" => $modeler::model(),
            "dom_id" => $dom_id
        ];

    }

    public static function sequencer($_model_id=0, $dom_id=null){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){return false;}

        return [
            "model" => $modeler::model(),
            "dom_id" => $dom_id
        ];

    }

}