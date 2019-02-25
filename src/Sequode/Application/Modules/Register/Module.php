<?php

namespace Sequode\Application\Modules\Register;

use Sequode\Application\Modules\User\Modeler as Modeler;

class Module {

    public static $registry_key = 'Register';

	public static function model(){

        return (object) [
            'context' => 'register',
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
    }
}