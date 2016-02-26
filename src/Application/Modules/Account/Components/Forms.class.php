<?php

namespace Sequode\Application\Modules\Account\Components;

use Sequode\Component\Form\Form as FormComponent;

use Sequode\Application\Modules\Account\Module;

class Forms {
    
	public static $module = Module::class;
    
    public static function updateEmail(){
        
        $module = static::$module;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        $o = (object) array(
            'form_inputs' => FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args()),
            'submit_xhr_call_route' => FormComponent::xhrCallRoute($context, 'operations', 'updateEmail'),
            'submit_button' => 'Next',
         );
        
		return FormComponent::formObject($o);
        
	}
    
    public static function verify(){
        
        $module = static::$module;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateEmail');
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
    public static function updatePassword(){
        
        $module = static::$module;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updatePassword');
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
    public static function password(){
        
        $module = static::$module;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updatePassword');
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
}