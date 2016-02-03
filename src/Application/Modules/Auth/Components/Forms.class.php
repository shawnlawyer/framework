<?php

namespace Sequode\Application\Modules\Auth\Components;

use Sequode\Component\Form\Form as FormComponent;

class Forms   {
    public static $package = 'Auth';
	public static $objects_source = FormInputs:class;
	public static $xhr_library = 'operations/auth';
	public static function login(){
        $_o = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $_o->submit_xhr_call_route = static::$xhr_library.'/'.'login';
        $_o->submit_button = 'Next';
		return $_o;
	}
    public static function secret(){
        $_o = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $_o->submit_xhr_call_route = static::$xhr_library.'/'.'login';
        $_o->submit_button = 'Next';
		return $_o;
	}
}