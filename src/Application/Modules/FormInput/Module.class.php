<?php

namespace Sequode\Application\Modules\FormInput;

class Module {
    
    public static $registry_key = 'FormInput';
    
	public static function model(){
        $_o = (object)  array (
        
            'context' => 'form_input',
            'modeler' => Modeler::class
        );
        
		return $_o;
        
	}
    
}