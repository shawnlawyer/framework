<?php

namespace Sequode\Application\Modules\Sequode\Components;

use Sequode\Component\Form\Form as FormComponent;

use Sequode\Application\Modules\Sequode\Module;

class Forms {
    
    public static $module = Module::class;
    
    public static function name($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject2($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateName');
        $_o->auto_submit_time = 2000;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        
		return $_o;
        
	}
    public static function description($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject2($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateDescription');
        $_o->auto_submit_time = 2000;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        
		return $_o;
        
	}
    public static function search(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        $_o = FormComponent::formObject2($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'search');
        
        $_o->auto_submit_time = 1;
        
		return $_o;
        
	}
    public static function component($type, $map_key, $_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject2($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateValue');
        $_o->auto_submit_time = 500;
        $_o->submit_on_enter = false;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = FormComponent::jsQuotedValue($type);
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = $map_key;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        
		return $_o;
        
	}
	public static function componentSettings($type, $member, $dom_id, $_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject2($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateComponentSettings');
        $_o->auto_submit_time = 1;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = FormComponent::jsQuotedValue($type);
        $_o->submit_xhr_call_parameters[] = FormComponent::jsQuotedValue($member);
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::jsQuotedValue($dom_id);
        
		return $_o;
        
	}
    public static function sequode($dom_id, $_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject2($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'run');
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
		$_o->submit_button = \Sequode\Application\Modules\Sequode\Modeler::model()->name;
        
		return $_o;
        
	}
    public static function sharing($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject2($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateSharing');
        $_o->auto_submit_time = 1;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
		return $_o;
	}
    public static function updateIsPalette($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject2($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateIsPalette');
        $_o->auto_submit_time = 1;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        
		return $_o;
        
	}
    public static function updateIsPackage($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
		
        $_o = FormComponent::formObject2($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'updateIsPackage');
        $_o->auto_submit_time = 1;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        
		return $_o;
        
	}
    public static function selectPalette(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model()->context;
        $form_inputs = $module::model()->components->form_inputs;
        
        ($_model == null) 
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model));
            
        $_o = FormComponent::formObject2($form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = FormComponent::xhrCallRoute($context, 'operations', 'selectPalette');
        $_o->auto_submit_time = 1;
        
		return $_o;
        
	}
}