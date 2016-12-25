<?php
namespace Sequode\Application\Modules\User\Components;

use Sequode\Component\Form\Form as FormComponent;

use Sequode\Application\Modules\User\Module;

class Forms  {
    
    public static $module = Module::class;
    
    public static function updateDomain($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateDomain');
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        $_o->submit_button = 'Save';
        
		return $_o;
        
	}
    
    public static function updateEmail($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateEmail');
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        $_o->submit_button = 'Save';
        
		return $_o;
        
	}
    
    public static function updatePassword($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updatePassword');
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        $_o->submit_button = 'Save';
        
		return $_o;
        
	}
    
    public static function search($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'search');
        $_o->auto_submit_time = 1;
        
		return $_o;
        
	}
    
    public static function updateRole($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
                    
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateRole');
        $_o->auto_submit_time = 1;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        
		return $_o;
	}
    public static function updateActive($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateActive');
        $_o->auto_submit_time = 1;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
		
        return $_o;
        
	}
    
    public static function updateName($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateName');
        $_o->auto_submit_time = 2000;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        
		return $_o;
        
	}
    
}