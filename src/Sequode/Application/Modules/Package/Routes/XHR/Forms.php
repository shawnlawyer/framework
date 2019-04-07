<?php

namespace Sequode\Application\Modules\Package\Routes\XHR;

use Sequode\Application\Modules\Package\Module;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
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

        return DOMElementKitJS::placeForm(ModuleForm::render($module::Registry_Key,__FUNCTION__), $dom_id);

    }

    public static function packageSequode($_model_id, $dom_id){

        extract((static::Module)::variables());

        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){return;}

        return DOMElementKitJS::placeForm(ModuleForm::render($module::Registry_Key,__FUNCTION__), $dom_id);

    }

}