<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Application\Modules\Traits\ModuleRoutesTrait;

class Module {

    use ModuleRoutesTrait;

    public static $registry_key = 'Session';

	public static function model(){

        return (object) [
            'context' => 'session',
            'modeler' => Modeler::class,
            //'store' => Store::class,
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
                'cards' => Routes\XHR\Cards::class
            ]
        ];

	}
}