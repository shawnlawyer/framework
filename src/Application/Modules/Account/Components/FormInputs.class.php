<?php

namespace Sequode\Application\Modules\Account\Components;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\FormInput\FormInput as FormInputComponent;

class FormInputs{
    public static $registry_key = 'Account';
    public static function updateEmail(){
        $modeler = ModuleRegistry::model(static::$registry_key)->modeler;
        $_model = $modeler::model();
        $_o = (object) null;
        
        FormInputComponent::exists('str','name');
		$_o->email = json_decode(FormInputComponent::model()->component_object);
        $_o->email->Label = 'Email Address';
        $_o->email->Value = $_model->email;
        $_o->email->Width = 200;
        
		return $_o;
	}
    public static function updatePassword(){
        $_o = (object) null;
        
        FormInputComponent::exists('password','name');
		$_o->password = json_decode(FormInputComponent::model()->component_object);
        $_o->password->Label = 'New Password';
        $_o->password->Value = '';
        $_o->password->Width = 200;
        $_o->password->CSS_Class = 'focus-input';
        
		$_o->confirm_password = json_decode(FormInputComponent::model()->component_object);
        $_o->confirm_password->Label = 'Confirm Password';
        $_o->confirm_password->Value = '';
        $_o->confirm_password->Width = 200;
        
		return $_o;
	}
    public static function password(){
        $_o = (object) null;
        
        FormInputComponent::exists('password','name');
		$_o->password = json_decode(FormInputComponent::model()->component_object);
        $_o->password->Label = 'Current Password';
        $_o->password->Value = '';
        $_o->password->Width = 200;
        $_o->password->CSS_Class = 'focus-input';
        
		return $_o;
	}
    public static function verify(){
        $_o = (object) null;
        
        FormInputComponent::exists('str','name');
        $_o->token = json_decode(FormInputComponent::model()->component_object);
        $_o->token->Label = '';
        $_o->token->Value = '';
        $_o->token->Width = 200;
        $_o->token->CSS_Class = 'focus-input';
        
		return $_o;
	}
}