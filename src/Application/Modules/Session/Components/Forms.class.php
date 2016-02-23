<?php

namespace Sequode\Application\Modules\Session\Components;

use Sequode\Component\Form\Form as FormComponent;

class Forms   {
	public static $objects_source = FormInputs::class;
	public static $xhr_library = 'operations/session';
    public static function search(){
        $_o = FormComponent::formObject(static::$objects_source, __FUNCTION__, static::$xhr_library, func_get_args());
        $_o->auto_submit_time = 1;
		return $_o;
	}
}