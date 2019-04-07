<?php

namespace Sequode\Application\Modules\Sequode\Components;

use Sequode\Component\Form as FormComponent;

use Sequode\Application\Modules\Sequode\Module;

class Forms {
    
    public static $module = Module::class;
    const Module = Module::class;

    public static function name($_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('updateName');
        $_o->auto_submit_time = 2000;
        $_o->submit_xhr_call_parameters = [];
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::Input_Object_Placeholder;
        
		return $_o;
        
	}

    public static function description($_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('updateDescription');
        $_o->auto_submit_time = 2000;
        $_o->submit_xhr_call_parameters = [];
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::Input_Object_Placeholder;
        
		return $_o;
        
	}

    public static function search(){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute(__FUNCTION__);
        $_o->auto_submit_time = 1;
        
		return $_o;
        
	}

    public static function component($type, $map_key, $_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('updateValue');
        $_o->auto_submit_time = 500;
        $_o->submit_on_enter = false;
        $_o->submit_xhr_call_parameters = [];
        $_o->submit_xhr_call_parameters[] = FormComponent::jsQuotedValue($type);
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = $map_key;
        $_o->submit_xhr_call_parameters[] = FormComponent::Input_Object_Placeholder;
        
		return $_o;
        
	}

	public static function componentSettings($type, $member, $dom_id, $_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('updateComponentSettings');
        $_o->auto_submit_time = 1;
        $_o->submit_xhr_call_parameters = [];
        $_o->submit_xhr_call_parameters[] = FormComponent::jsQuotedValue($type);
        $_o->submit_xhr_call_parameters[] = FormComponent::jsQuotedValue($member);
        $_o->submit_xhr_call_parameters[] = FormComponent::Input_Object_Placeholder;
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::jsQuotedValue($dom_id);
        
		return $_o;
        
	}

    public static function sequode($dom_id, $_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('run');
        $_o->submit_xhr_call_parameters = [];
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::Input_Object_Placeholder;
		$_o->submit_button = $modeler::model()->name;
        
		return $_o;
        
	}

    public static function sharing($_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('updateSharing');
        $_o->auto_submit_time = 1;
        $_o->submit_xhr_call_parameters = [];
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::Input_Object_Placeholder;

		return $_o;

	}

    public static function updateIsPalette($_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('updateIsPalette');
        $_o->auto_submit_time = 1;
        $_o->submit_xhr_call_parameters = [];
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::Input_Object_Placeholder;
        
		return $_o;
        
	}

    public static function updateIsPackage($_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('updateIsPackage');
        $_o->auto_submit_time = 1;
        $_o->submit_xhr_call_parameters = [];
        $_o->submit_xhr_call_parameters[] = $modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::Input_Object_Placeholder;
        
		return $_o;
        
	}

    public static function selectPalette(){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = FormComponent::formObject();
        $_o->form_inputs = FormComponent::formInputs($component_form_inputs, __FUNCTION__, func_get_args());
        $_o->submit_xhr_call_route = $module::xhrOperationRoute('selectPalette');
        $_o->auto_submit_time = 1;
        
		return $_o;
        
	}

}