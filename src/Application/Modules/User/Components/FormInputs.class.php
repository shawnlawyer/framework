<?php

namespace Sequode\Application\Modules\User\Components;

use Sequode\Application\Modules\User\Modeler as UserModeler;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\FormInput\FormInput as FormInputComponent;

class FormInputs{
    public static $registry_key = 'User';
    public static function updatePassword(){
        $modeler = ModuleRegistry::model(static::$registry_key)->modeler;
        $_o = (object) null;
        
        FormInputComponent::exists('password','name');
		$_o->password = json_decode(FormInputComponent::model()->component_object);
        $_o->password->Label = 'Password';
        $_o->password->Value = '';
        $_o->password->Width = 200;
        
		return $_o;
	}
    public static function updateEmail($_model = null){
        $modeler = ModuleRegistry::model(static::$registry_key)->modeler;
        $_model = ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : $_model;
        $_o = (object) null;
        
        FormInputComponent::exists('str','name');
		$_o->email = json_decode(FormInputComponent::model()->component_object);
        $_o->email->Label = 'Email';
        $_o->email->Value = $_model->email;
        $_o->email->Width = 200;
        
        FormInputComponent::exists('password','name');
		$_o->password = json_decode(FormInputComponent::model()->component_object);
        $_o->password->Label = 'Password';
        $_o->password->Value = '';
        $_o->password->Width = 200;
        $_o->password->CSS_Class = 'focus-input';
        
		return $_o;
	}
    public static function search(){
        
        $_o = (object) null;
        
        FormInputComponent::exists('str','name');
        $_o->search = json_decode(FormInputComponent::model()->component_object);
        $_o->search->Label = '';
        $_o->search->Value = '';
        $_o->search->Width = 200;
        $_o->search->CSS_Class = 'search-sequodes-input';
        
        FormInputComponent::exists('select','name');
        $_o->position = json_decode(FormInputComponent::model()->component_object);
        $_o->position->Label = '';
        $_o->position->Values = "[{'value':'=%','printable':'Starts With'},{'value':'%=%','printable':'Contains'},{'value':'%=','printable':'Ends With'},{'value':'=','printable':'Exact'}]";
        $_o->position->Value = '=%';
        $_o->position->Value_Key = 'value';
        $_o->position->Printable_Key = 'printable';
        
        FormInputComponent::exists('select','name');
        $_o->field = json_decode(FormInputComponent::model()->component_object);
        $_o->field->Label = '';
        $_o->field->Values = "[{'value':'username','printable':'Search By Username'},{'value':'email','printable':'Search By Email'}]";
        $_o->field->Value = 'username';
        $_o->field->Value_Key = 'value';
        $_o->field->Printable_Key = 'printable';
        
        FormInputComponent::exists('select','name');
        $_o->active = json_decode(FormInputComponent::model()->component_object);
        $_o->active->Label = '';
        $_o->active->Values = "[{'value':'all','printable':'Any'},{'value':'0','printable':'Unactivated'},{'value':'1','printable':'Active'},{'value':'2','printable':'Deactivated'}]";
        $_o->active->Value = 'all';
        $_o->active->Value_Key = 'value';
        $_o->active->Printable_Key = 'printable';

                
        $roles_model = new \Sequode\Application\Modules\Role\Modeler::$model;
        $roles_model->getAll();
        $values = array('{\'value\':\'all\',\'printable\':\'Any\'}');
        foreach( $roles_model->all as $object){
            $values[] = '{\'value\':\''.$object->id.'\',\'printable\':\''.$object->name.'\'}';
        }
        FormInputComponent::exists('select','name');
        $_o->role = json_decode(FormInputComponent::model()->component_object);
        $_o->role->Label = '';
        $_o->role->Values = '[' . implode(',',$values) . ']';
        $_o->role->Value = 'all';
        $_o->role->Value_Key = 'value';
        $_o->role->Printable_Key = 'printable';
		return $_o;
	}
    public static function updateRole($_model = null){
        $modeler = ModuleRegistry::model(static::$registry_key)->modeler;
        $_model = ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : $_model;
        $_o = (object) null;
        $roles_model = new \Sequode\Application\Modules\Role\Modeler::$model;
        $roles_model->getAll();
        foreach( $roles_model->all as $object){
            $values[] = '{\'value\':\''.$object->id.'\',\'printable\':\''.$object->name.'\'}';
        }
        FormInputComponent::exists('select','name');
        $_o->role = json_decode(FormInputComponent::model()->component_object);
        $_o->role->Label = '';
        $_o->role->Values = '[' . implode(',',$values) . ']';
        $_o->role->Value = $modeler::model()->role_id;
        $_o->role->Value_Key = 'value';
        $_o->role->Printable_Key = 'printable';
        
		return $_o;
	}
    public static function updateActive($_model = null){
        $modeler = ModuleRegistry::model(static::$registry_key)->modeler;
        $_model = ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : $_model;
        $_o = (object) null;
        FormInputComponent::exists('checkboxSwitch','name');
        $_o->active = json_decode(FormInputComponent::model()->component_object);
        $_o->active->Label = '';
        $_o->active->On_Text = 'Active';
        $_o->active->On_Value = 1;
        $_o->active->Off_Text = 'Suspended';
        $_o->active->Off_Value = 0;
        $_o->active->Value = $_model->active;
		return $_o;
	}
    public static function updateName($_model = null){
        $modeler = ModuleRegistry::model(static::$registry_key)->modeler;
        $_model = ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : $_model;
        $_o = (object) null;
        
        FormInputComponent::exists('str','name');
		$_o->username = json_decode(FormInputComponent::model()->component_object);
        $_o->username->Label = '';
        $_o->username->Value = $_model->username;
        $_o->username->Width = 200;
		return $_o;
	}
}