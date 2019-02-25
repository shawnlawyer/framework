<?php

namespace Sequode\Application\Modules\Account;

class Module {
    
    public static $registry_key = 'Account';
    
	public static function model(){
        $_o = (object)  [
        
            'context' => 'account',
            'modeler' => Modeler::class,
            'components' => (object) [
            
                'form_inputs' => Components\FormInputs::class,
                'forms' => Components\Forms::class,
                'cards' => Components\Cards::class,
                'dialogs' => Components\Dialogs::class

            ],
            'operations' => Operations::class,
            'xhr' => (object) [
            
                'operations' => Routes\XHR\Operations::class,
                'cards' => Routes\XHR\Cards::class

            ]

        ];
        
		return $_o;
        
	}
    
}