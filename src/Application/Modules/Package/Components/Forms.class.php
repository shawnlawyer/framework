<?php

namespace Sequode\Application\Modules\Package\Components;

use Sequode\Component\Form\Form as FormComponent;

class Forms   {
    public static $objects_source = FormInputs::class;
	public static $xhr_library = 'operations/package';
    public static function name($_model = null){
		$_o = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $_o->auto_submit_time = 2000;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_route = static::$xhr_library.'/'.'updateName';
        $_o->submit_xhr_call_parameters[] = \Sequode\Application\Modules\Package\Modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
		return $_o;
	}
    public static function packageSequode($_model = null){
		$_o = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $_o->auto_submit_time = 1;
        $_o->submit_xhr_call_parameters = array();
        $_o->submit_xhr_call_route = static::$xhr_library.'/'.'updatePackageSequode';
        $_o->submit_xhr_call_parameters[] = \Sequode\Application\Modules\Package\Modeler::model()->id;
        $_o->submit_xhr_call_parameters[] = FormComponent::$collection_replacement_hook;
		return $_o;
	}
    public static function search(){
        $_o = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $_o->auto_submit_time = 1;
		return $_o;
	}
}