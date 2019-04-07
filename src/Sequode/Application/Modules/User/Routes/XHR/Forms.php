<?php

namespace Sequode\Application\Modules\User\Routes\XHR;

use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

use Sequode\Application\Modules\User\Module;

class Forms {
    
    const Module = Module::class;
    
    public static function updatePassword($_model_id, $dom_id){
        extract((static::Module)::variables());
        
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        
        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    
    public static function updateEmail($_model_id, $dom_id){
        extract((static::Module)::variables());
        
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        
        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    public static function updateDomain($_model_id, $dom_id){
        extract((static::Module)::variables());
        
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        
        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    
    public static function updateRole($_model_id, $dom_id){
        extract((static::Module)::variables());
        
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        
        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    
    public static function updateActive($_model_id, $dom_id){
        extract((static::Module)::variables());
        
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        
        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    
    public static function updateName($_model_id, $dom_id){
        extract((static::Module)::variables());
        
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        
        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);
        
    }
}