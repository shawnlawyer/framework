<?php

namespace Sequode\Application\Modules\Account\Components;

use Sequode\Application\Modules\FormInput\Modeler as FormInputModeler;

use Sequode\Application\Modules\Account\Module;

class FormInputs{
    
    public static $module = Module::class;
    
    public static function updateEmail(){
        
        extract((static::Module)::variables());
        $_o = (object) null;
        
        FormInputModeler::exists('str','name');
		$_o->email = FormInputModeler::model()->component_object;
        $_o->email->Label = 'Email Address';
        $_o->email->Value = $modeler::model()->email;
        $_o->email->Width = 200;
        
		return $_o;
        
	}
    
    public static function updatePassword(){
        
        $_o = (object) null;
        
        FormInputModeler::exists('password','name');
		$_o->password = FormInputModeler::model()->component_object;
        $_o->password->Label = 'New Password';
        $_o->password->Value = '';
        $_o->password->Width = 200;
        $_o->password->CSS_Class = 'focus-input';
        
		$_o->confirm_password = FormInputModeler::model()->component_object;
        $_o->confirm_password->Label = 'Confirm Password';
        $_o->confirm_password->Value = '';
        $_o->confirm_password->Width = 200;
        
		return $_o;
        
	}
    
    public static function password(){
        
        $_o = (object) null;
        
        FormInputModeler::exists('password','name');
		$_o->password = FormInputModeler::model()->component_object;
        $_o->password->Label = 'Current Password';
        $_o->password->Value = '';
        $_o->password->Width = 200;
        $_o->password->CSS_Class = 'focus-input';
        
		return $_o;
        
	}
    
    public static function verify(){
        
        $_o = (object) null;
        
        FormInputModeler::exists('str','name');
        $_o->token = FormInputModeler::model()->component_object;
        $_o->token->Label = '';
        $_o->token->Value = '';
        $_o->token->Width = 200;
        $_o->token->CSS_Class = 'focus-input';
        
		return $_o;
        
	}
    
}
