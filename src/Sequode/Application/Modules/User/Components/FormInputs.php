<?php

namespace Sequode\Application\Modules\User\Components;

use Sequode\Application\Modules\FormInput\Modeler as FormInputModeler;
use Sequode\Application\Modules\Role\Modeler as RoleModeler;

use Sequode\Application\Modules\User\Module;

class FormInputs{
    
    const Module = Module::class;
    
    public static function updatePassword(){
            
        $_o = (object) null;
        
        FormInputModeler::exists('password','name');
		$_o->password = FormInputModeler::model()->component_object;
        $_o->password->Label = 'Password';
        $_o->password->Value = '';
        $_o->password->Width = 200;
        
		return $_o;
        
	}

    public static function updateEmail($_model = null){

        extract((static::Module)::variables());
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
		
        $_o = (object) null;
        
        FormInputModeler::exists('str','name');
		$_o->email = FormInputModeler::model()->component_object;
        $_o->email->Label = 'Email';
        $_o->email->Value = $_model->email;
        $_o->email->Width = 200;
        
        FormInputModeler::exists('password','name');
		$_o->password = FormInputModeler::model()->component_object;
        $_o->password->Label = 'Password';
        $_o->password->Value = '';
        $_o->password->Width = 200;
        $_o->password->CSS_Class = 'focus-input';
        
		return $_o;
        
	}

    public static function search(){
        
        $_o = (object) null;
        
        FormInputModeler::exists('str','name');
        $_o->search = FormInputModeler::model()->component_object;
        $_o->search->Label = '';
        $_o->search->Value = '';
        $_o->search->Width = 200;
        $_o->search->CSS_Class = 'search-sequodes-input';
        
        FormInputModeler::exists('select','name');
        $_o->position = FormInputModeler::model()->component_object;
        $_o->position->Label = '';
        $_o->position->Values = "[{'value':'=%','printable':'Starts With'},{'value':'%=%','printable':'Contains'},{'value':'%=','printable':'Ends With'},{'value':'=','printable':'Exact'}]";
        $_o->position->Value = '=%';
        $_o->position->Value_Key = 'value';
        $_o->position->Printable_Key = 'printable';
        
        FormInputModeler::exists('select','name');
        $_o->field = FormInputModeler::model()->component_object;
        $_o->field->Label = '';
        $_o->field->Values = "[{'value':'name','printable':'Search By Name'},{'value':'email','printable':'Search By Email'}]";
        $_o->field->Value = 'name';
        $_o->field->Value_Key = 'value';
        $_o->field->Printable_Key = 'printable';
        
        FormInputModeler::exists('select','name');
        $_o->active = FormInputModeler::model()->component_object;
        $_o->active->Label = '';
        $_o->active->Values = "[{'value':'all','printable':'Any'},{'value':'0','printable':'Unactivated'},{'value':'1','printable':'Active'},{'value':'2','printable':'Deactivated'}]";
        $_o->active->Value = 'all';
        $_o->active->Value_Key = 'value';
        $_o->active->Printable_Key = 'printable';
                
        $roles_model = new RoleModeler::$model;
        $roles_model->getAll();
        $values = ['{\'value\':\'all\',\'printable\':\'Any\'}'];
        foreach( $roles_model->all as $object){
            $values[] = '{\'value\':\''.$object->id.'\',\'printable\':\''.$object->name.'\'}';
        }

        FormInputModeler::exists('select','name');
        $_o->role = FormInputModeler::model()->component_object;
        $_o->role->Label = '';
        $_o->role->Values = '[' . implode(',',$values) . ']';
        $_o->role->Value = 'all';
        $_o->role->Value_Key = 'value';
        $_o->role->Printable_Key = 'printable';
        
		return $_o;
        
	}

    public static function updateRole($_model = null){

        extract((static::Module)::variables());
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
		
        $_o = (object) null;

        $roles_model = new RoleModeler::$model;
        $roles_model->getAll();

        foreach( $roles_model->all as $object){

            $values[] = '{\'value\':\''.$object->id.'\',\'printable\':\''.$object->name.'\'}';

        }

        FormInputModeler::exists('select','name');
        $_o->role = FormInputModeler::model()->component_object;
        $_o->role->Label = '';
        $_o->role->Values = '[' . implode(',',$values) . ']';
        $_o->role->Value = $modeler::model()->role_id;
        $_o->role->Value_Key = 'value';
        $_o->role->Printable_Key = 'printable';
        
		return $_o;
        
	}
    public static function updateActive($_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
		
        $_o = (object) null;
        FormInputModeler::exists('checkboxSwitch','name');
        $_o->active = FormInputModeler::model()->component_object;
        $_o->active->Label = '';
        $_o->active->On_Text = 'Active';
        $_o->active->On_Value = 1;
        $_o->active->Off_Text = 'Suspended';
        $_o->active->Off_Value = 0;
        $_o->active->Value = $modeler::model()->active;
        
		return $_o;
        
	}
    public static function updateName($_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = (object) null;
        FormInputModeler::exists('str','name');
		$_o->name = FormInputModeler::model()->component_object;
        $_o->name->Label = '';
        $_o->name->Value = $modeler::model()->name;
        $_o->name->Width = 200;
        
		return $_o;
        
	}

}