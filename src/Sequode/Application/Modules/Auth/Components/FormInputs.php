<?php

namespace Sequode\Application\Modules\Auth\Components;

use Sequode\Application\Modules\FormInput\Modeler as FormInputModeler;
use Sequode\Application\Modules\Auth\Module;

class FormInputs {
    
    public static $module = Module::class;
    
    public static function login(){
        
        $_o = (object) null;

        FormInputModeler::exists('str','name');
        $_o->login = json_decode(FormInputModeler::model()->component_object);
        $_o->login->Label = '';
        $_o->login->Value = '';
        $_o->login->Width = 200;
        $_o->login->CSS_Class = 'focus-input';

		return $_o;
        
	}
    
    public static function secret(){
        
        $_o = (object) null;
        
        FormInputModeler::exists('password','name');
		$_o->secret = json_decode(FormInputModeler::model()->component_object);
        $_o->secret->Label = '';
        $_o->secret->Value = '';
        $_o->secret->Width = 200;
        $_o->secret->CSS_Class = 'focus-input';
        
		return $_o;
        
	}
    
}