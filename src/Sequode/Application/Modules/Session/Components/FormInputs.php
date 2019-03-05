<?php

namespace Sequode\Application\Modules\Session\Components;

use Sequode\Application\Modules\FormInput\Modeler as FormInputModeler;

class FormInputs{
    
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
        $_o->field->Values = "[{'value':'name','printable':'Search By Name'},{'value':'ip_address','printable':'Search By IP'}]";
        $_o->field->Value = 'name';
        $_o->field->Value_Key = 'value';
        $_o->field->Printable_Key = 'printable';
        
		return $_o;
        
	}
    
}