<?php

namespace Sequode\Application\Modules\User\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

class Forms {
    public static $module_registry_key = Sequode\Application\Modules\User\Module::class;
    public static function updatePassword($_model_id, $dom_id){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        return DOMElementKitJS::placeForm(ModuleForm::render(static::$module_registry_key,__FUNCTION__), $dom_id);
    }
    public static function updateEmail($_model_id, $dom_id){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        return DOMElementKitJS::placeForm(ModuleForm::render(static::$module_registry_key,__FUNCTION__), $dom_id);
    }
    public static function updateDomain($_model_id, $dom_id){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        return DOMElementKitJS::placeForm(ModuleForm::render(static::$module_registry_key,__FUNCTION__), $dom_id);
    }
    public static function updateRole($_model_id, $dom_id){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        return DOMElementKitJS::placeForm(ModuleForm::render(static::$module_registry_key,__FUNCTION__), $dom_id);
    }
    public static function updateActive($_model_id, $dom_id){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        return DOMElementKitJS::placeForm(ModuleForm::render(static::$module_registry_key,__FUNCTION__), $dom_id);
    }
    public static function updateName($_model_id, $dom_id){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        return DOMElementKitJS::placeForm(ModuleForm::render(static::$module_registry_key,__FUNCTION__), $dom_id);
    }
}