<?php

namespace Sequode\Application\Modules\Auth;

use Sequode\Application\Modules\Account\Modeler;
use Sequode\Application\Modules\Traits\ModuleRoutesTrait;

class Module {

    use ModuleRoutesTrait;

    public static $registry_key = 'Auth';

	public static function model(){
        $_o = (object)  [
            'context' => 'auth',
            'modeler' => Modeler::class,
            'components' => (object) [
                'form_inputs' => Components\FormInputs::class,
                'forms' => Components\Forms::class,
                'cards' => Components\Cards::class,
                'dialogs' => Components\Dialogs::class,
            ],
            'operations' => Operations::class,
            'xhr' => (object) [
                'operations' => Routes\XHR\Operations::class,
                'cards' => Routes\XHR\Cards::class,
            ]
        ];
		return $_o;
	}
}