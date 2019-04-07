<?php

namespace Sequode\Application\Modules\Session\Components;

use Sequode\Component\Form as FormComponent;

use Sequode\Application\Modules\Session\Module;

class Forms   {
    
    const Module = Module::class;

    public static function search(){

        extract((static::Module)::variables());
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute(__FUNCTION__);
        $_o->auto_submit_time = 1;
        
		return $_o;
        
	}

}