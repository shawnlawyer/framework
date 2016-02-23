<?php

namespace Sequode\Application\Modules\Token\Components;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\FormInput\FormInput as FormInputComponent;


use Sequode\Application\Modules\Token\Module;
    
class FormInputs{
    
    public static $module = Module::class;
    
    public static function name($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $_model = ($_model == null) ? forward_static_call_array(array($modeler, 'model'), array()) : $_model;
        $_o = (object) null;
        
        FormInputComponent::exists('str','name');
        $_o->name = json_decode(FormInputComponent::model()->component_object);
        $_o->name->Label = '';
        $_o->name->Value = $_model->name;
        $_o->name->Width = 200;
        $_o->name->CSS_Class = 'focus-input';
        
		return $_o;
        
	}
    public static function search(){
        $_o = (object) null;
        
        FormInputComponent::exists('str','name');
        $_o->search = json_decode(FormInputComponent::model()->component_object);
        $_o->search->Label = '';
        $_o->search->Value = '';
        $_o->search->Width = 200;
        $_o->search->CSS_Class = 'focus-input';
        
        FormInputComponent::exists('select','name');
        $_o->position = json_decode(FormInputComponent::model()->component_object);
        $_o->position->Label = '';
        $_o->position->Values = "[{'value':'=%','printable':'Starts With'},{'value':'%=%','printable':'Contains'},{'value':'%=','printable':'Ends With'},{'value':'=','printable':'Exact'}]";
        $_o->position->Value = '=%';
        $_o->position->Value_Key = 'value';
        $_o->position->Printable_Key = 'printable';
        
		return $_o;
	}
}