<?php

namespace Sequode\Application\Modules\Sequode;

use Sequode\Application\Modules\Traits\ModuleRoutesTrait;

class Module {

    use ModuleRoutesTrait;

    const Registry_Key = 'Sequode';

	public static function model(){
        $_o = (object)  [
            'context' => 'sequode',
            'finder' => Collections::class,
            'collections' => Routes\Collections\Collections::class,
            'modeler' => Modeler::class,
            'components' => (object) [
                'form_inputs' => Components\FormInputs::class,
                'forms' => Components\Forms::class,
                'cards' => Components\Cards::class,
            ],
            'operations' => Operations::class,
            'operations_kit' => Kits\Operations::class,
            'xhr' => (object) [
                'operations' => Routes\XHR\Operations::class,
                'forms' => Routes\XHR\Forms::class,
                'cards' => Routes\XHR\Cards::class
            ],
            'rest' => (object) [
                'operations' => Routes\Rest\Operations::class,
                'downloads' => Routes\Rest\Downloads::class
            ]
        ];
        return $_o;
    }
}