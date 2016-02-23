<?php

namespace Sequode\Application\Modules\Token\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

use Sequode\Application\Modules\Token\Module;

class Forms {
    public static $module = Module::class;
    
    public static function name($_model_id, $dom_id){    
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(!(
        $modeler::exists($_model_id,'id')
        && (\Sequode\Application\Modules\Account\Authority::isOwner( $modeler::model() )
        || \Sequode\Application\Modules\Account\Authority::isSystemOwner())
        )){return;}
        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
}