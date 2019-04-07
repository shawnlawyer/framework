<?php

namespace Sequode\Application\Modules\FormInput;

class Module {
    
    const Registry_Key = 'FormInput';
    
	public static function model(){
        $_o = (object)  [
        
            'context' => 'form_input',
            'modeler' => Modeler::class
        ];
        
		return $_o;
        
	}
    
}