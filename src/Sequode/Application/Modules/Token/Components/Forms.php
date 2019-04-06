<?php

namespace Sequode\Application\Modules\Token\Components;

use Sequode\Component\Form as FormComponent;

use Sequode\Application\Modules\Token\Module;

class Forms   {
    
    public static $module = Module::class;
    const Module = Module::class;

    public static function name(){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateName');
        $_o->auto_submit_time = 2000;
        $_o->submit_xhr_call_parameters = [];
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        
		return $_o;
        
	}
    
    public static function search(){

        extract((static::Module)::variables());

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', __FUNCTION__);
        $_o->auto_submit_time = 1;
        
		return $_o;
        
	}
    
}