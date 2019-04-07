<?php

namespace Sequode\Application\Modules\Account\Components;

use Sequode\Component\Form as FormComponent;

use Sequode\Application\Modules\Account\Module;

class Forms {
    
	public static $module = Module::class;
	const Module = Module::class;

    public static function updateEmail(){

        extract((static::Module)::variables());

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('updateEmail');
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
    public static function verify(){

        extract((static::Module)::variables());

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('updateEmail');
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
    public static function updatePassword(){

        extract((static::Module)::variables());

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('updatePassword');
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
    public static function password(){

        extract((static::Module)::variables());

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, 'updatePassword', func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute(updatePassword);
        $_o->submit_button = 'Next';
        
		return $_o;
        
	}
    
}