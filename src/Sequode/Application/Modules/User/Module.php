<?php

namespace Sequode\Application\Modules\User;

use Sequode\Application\Modules\Traits\ModuleRoutesTrait;

class Module {

    use ModuleRoutesTrait;

    const Registry_Key = 'User';

	public static function model(){

        return (object)  [
            'context' => 'user',
            'modeler' => Modeler::class,
            'components' => (object) [
                'form_inputs' => Components\FormInputs::class,
                'forms' => Components\Forms::class,
                'cards' => Components\Cards::class,
            ],
            'operations' => Operations::class,
            'authority' => Authority::class,
            'finder' => Collections::class,
            'collections' => Routes\Collections\Collections::class,
            'xhr' => (object) [
                'operations' => Routes\XHR\Operations::class,
                'forms' => Routes\XHR\Forms::class,
                'cards' => Routes\XHR\Cards::class
            ]
        ];

	}
}