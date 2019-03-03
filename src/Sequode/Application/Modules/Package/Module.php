<?php

namespace Sequode\Application\Modules\Package;

use Sequode\Application\Modules\Traits\ModuleRoutesTrait;

class Module {

    use ModuleRoutesTrait;

    public static $registry_key = 'Package';

	public static function model(){
        $_o = (object)  [
            'context' => 'package',
            'finder' => Collections::class,
            'collections' => Routes\Collections\Collections::class,
            'modeler' => Modeler::class,
            'components' => (object) [
                'form_inputs' => Components\FormInputs::class,
                'forms' => Components\Forms::class,
                'cards' => Components\Cards::class,
            ],
            'operations' => Operations::class,
            'routes' => [
                'downloads' => Routes\Rest\Downloads::class
            ],
            'xhr' => (object) [
                'operations' => Routes\XHR\Operations::class,
                'forms' => Routes\XHR\Forms::class,
                'cards' => Routes\XHR\Cards::class
            ],
            'rest' => (object) [
                'downloads' => Routes\Rest\Downloads::class
            ]
        ];
        return $_o;
    }
}