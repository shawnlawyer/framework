<?php

namespace Sequode\Application\Modules\Token\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

class Forms {
    public static $package = 'Token';
    public static function name($_model_id, $dom_id){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        if(!(
        $modeler::exists($_model_id,'id')
        && (\SQDE_UserAuthority::isOwner( $modeler::model() )
        || \SQDE_UserAuthority::isSystemOwner())
        )){return;}
        return DOMElementKitJS::placeForm(ModuleForm::render(self::$package,__FUNCTION__), $dom_id);
    }
}