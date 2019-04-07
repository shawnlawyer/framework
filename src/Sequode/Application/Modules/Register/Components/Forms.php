<?php

namespace Sequode\Application\Modules\Register\Components;

use Sequode\Application\Modules\Register\Module;
use Sequode\Component\Form as FormComponent;

class Forms {
    
    public static $module = Module::class;
    const Module = Module::class;

    public static function email(){

        extract((static::Module)::variables());

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('signup');
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
    public static function password(){

        extract((static::Module)::variables());

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('signup');
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
    public static function verify(){

        extract((static::Module)::variables());

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('signup');
        $_o->auto_submit_time = 1;
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
	public static function terms(){

        extract((static::Module)::variables());

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());

		return $_o;
	}
    
	public static function acceptTerms(){

        extract((static::Module)::variables());

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('signup');
        $_o->auto_submit_time = 1;
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
    public static function name(){

        extract((static::Module)::variables());
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('signup');
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
}