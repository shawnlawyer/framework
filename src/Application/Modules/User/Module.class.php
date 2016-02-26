<?php

namespace Sequode\Application\Modules\User;

class Module {
    public static $registry_key = 'User';
	public static function model(){
        $_o = (object)  array (
            'context' => 'user',
            'modeler' => Modeler::class,
            'components' => (object) array (
                'form_inputs' => Components\FormInputs::class,
                'forms' => Components\Forms::class,
                'cards' => Components\Cards::class,
            ),
            'operations' => Operations::class,
            'finder' => Collections::class,
            'collections' => Routes\Collections\Collections::class,
            'xhr' => (object) array (
                'operations' => Routes\XHR\Operations::class,
                'forms' => Routes\XHR\Forms::class,
                'cards' => Routes\XHR\Cards::class
            )
        );
		return $_o;
	}
}