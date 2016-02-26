<?php

namespace Sequode\Application\Modules\Token\Components;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Form\Form as FormComponent;

use Sequode\Application\Modules\Token\Module;

class Forms   {
    
    public static $module = Module::class;
    
    public static function name(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject2($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateName');
        $_o->auto_submit_time = 2000;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        
		return $_o;
        
	}
    public static function search(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
            
        $_o = FormComponent::formObject2($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'search');
        $_o->auto_submit_time = 1;
        
		return $_o;
        
	}
}