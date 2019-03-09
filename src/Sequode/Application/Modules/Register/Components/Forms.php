<?php

namespace Sequode\Application\Modules\Register\Components;

use Sequode\Application\Modules\Register\Module;
use Sequode\Component\Form as FormComponent;

class Forms {
    
    public static $module = Module::class;
    
    public static function email(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'signup');
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
    public static function password(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'signup');
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
    public static function verify(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'signup');
        $_o->auto_submit_time = 1;
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
	public static function terms(){
        
        $module = static::$module;
        $form_inputs = $module::model()->components->form_inputs;
        
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        
		return $_o;
	}
    
	public static function acceptTerms(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'signup');
        $_o->auto_submit_time = 1;
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
    public static function name(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'signup');
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
}