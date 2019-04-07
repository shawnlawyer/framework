<?php

namespace Sequode\Application\Modules\Register\Components;

use Sequode\Application\Modules\Register\Module;
use Sequode\Application\Modules\FormInput\Modeler as FormInputModeler;


class FormInputs   {

    const Module = Module::class;
    
    public static function email(){
        
        $_o = (object) null;
        
        FormInputModeler::exists('str','name');
        $_o->email = FormInputModeler::model()->component_object;
        $_o->email->Label = '';
        $_o->email->Value = '';
        $_o->email->Width = 200;
        $_o->email->CSS_Class = 'focus-input';
        
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
    public static function acceptTerms(){
        
        $_o = (object) null;
        
        FormInputModeler::exists('checkboxSwitch','name');
        $_o->accept = FormInputModeler::model()->component_object;
        $_o->accept->Label = '';
        $_o->accept->On_Text = 'I Accept';
        $_o->accept->On_Value = 1;
        $_o->accept->Off_Text = 'I Accept';
        $_o->accept->Off_Value = 0;
        $_o->accept->Value = 0;
        $_o->accept->CSS_Class = 'focus-input';
        
		return $_o;
        
	}
    public static function terms(){
        
        $_o = (object) null;
        
        FormInputModeler::exists('text','name');
        $_o->terms = FormInputModeler::model()->component_object;
        $_o->terms->Label = '';
        $_o->terms->Value = strip_tags(file_get_contents('text/terms-conditions.txt',true));
        $_o->terms->Width = 23;
        $_o->terms->Height = 18;
        
		return $_o;
        
	}

    public static function password(){
        $_o = (object) null;
        
        FormInputModeler::exists('password','name');
		$_o->password = FormInputModeler::model()->component_object;
        $_o->password->Label = '';
        $_o->password->Value = '';
        $_o->password->Width = 200;
        $_o->password->CSS_Class = 'focus-input';
        
        FormInputModeler::exists('password','name');
		$_o->confirm_password = FormInputModeler::model()->component_object;
        $_o->confirm_password->Label = 'Confirm Password';
        $_o->confirm_password->Value = '';
        $_o->confirm_password->Width = 200;
        
		return $_o;
        
	}
}