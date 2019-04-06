<?php

namespace Sequode\Application\Modules\Token\Routes\XHR;

use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

use Sequode\Application\Modules\Token\Module;

use Sequode\Application\Modules\Account\Authority as AccountAuthority;
class Forms {

    public static $module = Module::class;
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
}