<?php

namespace Sequode\Application\Modules\Auth\Components;

use Sequode\Component\Form as FormComponent;

use Sequode\Application\Modules\Auth\Module;

class Forms {
    
    public static $module = Module::class;
    
	public static function login(){
        
        $module = static::$module;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'login');
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
    public static function secret(){
        
        $module = static::$module;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'login');
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
}