<?php

namespace Sequode\Application\Modules\Sequode\Routes\XHR;

use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

use Sequode\Application\Modules\Sequode\Module;

use Sequode\Application\Modules\Account\Authority as AccountAuthority;

class Forms {
    
    const Module = Module::class;
    
    public static function name($_model_id, $dom_id){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){return;}

        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);

    }

    public static function description($_model_id, $dom_id){

        extract((static::Module)::variables());

        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){return;}

        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);

    }

    public static function component($type, $_model_id, $map_key, $dom_id){

        extract((static::Module)::variables());

        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
            && in_array($type, ['input','property'])
        )){return;}

        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__, [$type, $map_key]), $dom_id);

    }

    public static function componentSettings($type, $member, $_model_id, $dom_id){

        extract((static::Module)::variables());

        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
            && in_array($type, ['input','property'])
        )){return;}

        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__, [$type, $member, $dom_id]), $dom_id);

    }

    public static function sequode($_model_id, $dom_id){

        extract((static::Module)::variables());

        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){return;}

        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);

    }

    public static function updateIsPalette($_model_id, $dom_id){

        extract((static::Module)::variables());

        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){return;}

        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);

    }

    public static function updateIsPackage($_model_id, $dom_id){

        extract((static::Module)::variables());

        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){return;}

        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);

    }

    public static function sharing($_model_id, $dom_id){

        extract((static::Module)::variables());

        if(!(
            $modeler::exists($_model_id,'id')
            && AccountAuthority::isSystemOwner()
        )){return;}

        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);

    }

    public static function selectPalette($dom_id){

        extract((static::Module)::variables());

        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);

    }
}