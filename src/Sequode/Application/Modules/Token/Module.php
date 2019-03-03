<?php

namespace Sequode\Application\Modules\Token;

use Sequode\Application\Modules\Traits\ModuleRoutesTrait;

class Module {

    use ModuleRoutesTrait;

    public static $registry_key = 'Token';

	public static function model(){
        $_o = (object)  [
            'context' => 'token',
            'modeler' => Modeler::class,
            'operations' => Operations::class,
            'components' => (object) [
                'form_inputs' => Components\FormInputs::class,
                'forms' => Components\Forms::class,
                'cards' => Components\Cards::class,
            ],
            'finder' => Collections::class,
            'collections' => Routes\Collections\Collections::class,
            'xhr' => (object) [
                'operations' => Routes\XHR\Operations::class,
                'forms' => Routes\XHR\Forms::class,
                'cards' => Routes\XHR\Cards::class
            ]
        ];
		return $_o;
	}
}