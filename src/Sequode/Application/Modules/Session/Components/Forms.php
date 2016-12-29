<?php

namespace Sequode\Application\Modules\Session\Components;

use Sequode\Component\Form\Form as FormComponent;

use Sequode\Application\Modules\User\Module;

class Forms   {
    
    public static $module = Module::class;
    
    public static function search(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'search');
        $_o->auto_submit_time = 1;
        
		return $_o;
        
	}
}