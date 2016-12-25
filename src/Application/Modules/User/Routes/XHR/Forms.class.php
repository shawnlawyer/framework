<?php

namespace Sequode\Application\Modules\User\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

use Sequode\Application\Modules\User\Module;

class Forms {
    
    public static $module = Module::class;
    
    public static function updatePassword($_model_id, $dom_id){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        
        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    
    public static function updateEmail($_model_id, $dom_id){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        
        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    public static function updateDomain($_model_id, $dom_id){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        
        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    
    public static function updateRole($_model_id, $dom_id){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        
        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    
    public static function updateActive($_model_id, $dom_id){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        
        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    
    public static function updateName($_model_id, $dom_id){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(!(
        $modeler::exists($_model_id,'id')
        )){return;}
        
        return DOMElementKitJS::placeForm(ModuleForm::render($module::$registry_key, __FUNCTION__), $dom_id);
        
    }
}