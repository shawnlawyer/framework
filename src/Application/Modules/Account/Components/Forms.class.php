<?php

namespace Sequode\Application\Modules\Account\Components;

use Sequode\Component\Form\Form as FormComponent;

class Forms   {
	public static $objects_source = FormInputs::class;
	public static $xhr_library = 'operations/account';
    public static function updateEmail(){
        $_o = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $_o->submit_xhr_call_route = static::$xhr_library.'/'.'updateEmail';
        $_o->submit_button = 'Next';
		return $_o;
	}
    public static function verify(){
        $_o = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $_o->submit_xhr_call_route = static::$xhr_library.'/'.'updateEmail';
        $_o->submit_button = 'Next';
		return $_o;
	}
    public static function updatePassword(){
        $_o = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $_o->submit_xhr_call_route = static::$xhr_library.'/'.'updatePassword';
        $_o->submit_button = 'Next';
		return $_o;
	}
    public static function password(){
        $_o = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $_o->submit_xhr_call_route = static::$xhr_library.'/'.'updatePassword';
        $_o->submit_button = 'Next';
		return $_o;
	}
}