<?php

namespace Sequode\Application\Modules\Sequode\Components;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Form\Form as FormComponent;

class Forms {
    public static $module_registry_key = Sequode\Application\Modules\Sequode\Module::class;
    public static $objects_source = FormInputs::class;
	public static $xhr_library = 'operations/sequode';
    public static function name($_model = null){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
		$form_object = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $form_object->auto_submit_time = 2000;
        $form_object->submit_xhr_call_parameters = array();
        $form_object->submit_xhr_call_route = static::$xhr_library.'/'.'updateName';
        $form_object->submit_xhr_call_parameters[] = $modeler::model()->id;
        $form_object->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
		return $form_object;
	}
    public static function description($_model = null){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
		$form_object = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $form_object->auto_submit_time = 2000;
        $form_object->submit_xhr_call_parameters = array();
        $form_object->submit_xhr_call_route = static::$xhr_library.'/'.'updateDescription';
        $form_object->submit_xhr_call_parameters[] = $modeler::model()->id;
        $form_object->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
		return $form_object;
	}
    public static function search(){
        $form_object = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $form_object->auto_submit_time = 1;
		return $form_object;
	}
    public static function component($type, $map_key, $_model = null){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $form_object = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $form_object->auto_submit_time = 500;
        $form_object->submit_on_enter = false;
        $form_object->submit_xhr_call_route = static::$xhr_library.'/'.'updateValue';
        $form_object->submit_xhr_call_parameters = array();
        $form_object->submit_xhr_call_parameters[] = FormComponent::jsQuotedValue($type);
        $form_object->submit_xhr_call_parameters[] = $modeler::model()->id;
        $form_object->submit_xhr_call_parameters[] = $map_key;
        $form_object->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
		return $form_object;
	}
	public static function componentSettings($type, $member, $dom_id, $_model = null){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $form_object = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $form_object->auto_submit_time = 1;
        $form_object->submit_xhr_call_route = static::$xhr_library.'/'.'updateComponentSettings';
        $form_object->submit_xhr_call_parameters = array();
        $form_object->submit_xhr_call_parameters[] = FormComponent::jsQuotedValue($type);
        $form_object->submit_xhr_call_parameters[] = FormComponent::jsQuotedValue($member);
        $form_object->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
        $form_object->submit_xhr_call_parameters[] = $modeler::model()->id;
        $form_object->submit_xhr_call_parameters[] = FormComponent::jsQuotedValue($dom_id);
		return $form_object;
	}
    public static function sequode($dom_id, $_model = null){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $form_object = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $form_object->submit_xhr_call_route = static::$xhr_library.'/'.'run';
        $form_object->submit_xhr_call_parameters = array();
        $form_object->submit_xhr_call_parameters[] = $modeler::model()->id;
        $form_object->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
		$form_object->submit_button = \Sequode\Application\Modules\Sequode\Modeler::model()->name;
		return $form_object;
	}
    public static function sharing($_model = null){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));   
        $form_object = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $form_object->auto_submit_time = 1;
        $form_object->submit_xhr_call_route = static::$xhr_library.'/'.'updateSharing';
        $form_object->submit_xhr_call_parameters = array();
        $form_object->submit_xhr_call_parameters[] = $modeler::model()->id;
        $form_object->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
		return $form_object;
	}
    public static function updateIsPalette($_model = null){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $form_object = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $form_object->auto_submit_time = 1;
        $form_object->submit_xhr_call_route = static::$xhr_library.'/'.'updateIsPalette';
        $form_object->submit_xhr_call_parameters = array();
        $form_object->submit_xhr_call_parameters[] = $modeler::model()->id;
        $form_object->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
		return $form_object;
	}
    public static function updateIsPackage($_model = null){
        $modeler = ModuleRegistry::model(static::$module_registry_key)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $form_object = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $form_object->auto_submit_time = 1;
        $form_object->submit_xhr_call_route = static::$xhr_library.'/'.'updateIsPackage';
        $form_object->submit_xhr_call_parameters = array();
        $form_object->submit_xhr_call_parameters[] = $modeler::model()->id;
        $form_object->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
		return $form_object;
	}
    public static function selectPalette(){
        $form_object = FormComponent::formObject(static::$objects_source,__FUNCTION__,static::$xhr_library,func_get_args());
        $form_object->auto_submit_time = 1;
		return $form_object;
	}
}