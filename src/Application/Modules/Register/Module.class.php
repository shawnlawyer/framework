<?php

namespace Sequode\Application\Modules\Register;

class Module {
    public static $registry_key = 'Register';
	public static function model(){
        $_o = (object)  array (
            'context' => 'register',
            'modeler' => \Sequode\Application\Modules\User\Modeler::class,
            'components' => (object) array (
                'forms' => Components\Forms::class,
                'cards' => Components\Cards::class,
                'dialogs' => Components\Dialogs::class
            ),
            'operations' => Operations::class,
            'xhr' => (object) array (
                'operations' => Routes\XHR\Operations::class,
                'cards' => Routes\XHR\Cards::class
            )
        );
        return $_o;
    }
}