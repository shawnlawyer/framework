<?php

namespace Sequode\Application\Modules\Package\Components;

use Sequode\Component\Form\Form as FormComponent;

use Sequode\Application\Modules\Package\Module;

class Forms {
    
    public static $module = Module::class;
    
    public static function name($_model = null){
        
        $module = static::$module;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateName');
        $_o->auto_submit_time = 2000;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = \Sequode\Application\Modules\Package\Modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        
		return $_o;
        
	}
    
    public static function packageSequode($_model = null){
        
        $module = static::$module;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updatePackageSequode');
        $_o->auto_submit_time = 1;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = \Sequode\Application\Modules\Package\Modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        
		return $_o;
        
	}
    
    public static function search(){
        
        $module = static::$module;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'search');
        $_o->auto_submit_time = 1;
        
		return $_o;
        
	}
}