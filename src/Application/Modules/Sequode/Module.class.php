<?php

namespace Sequode\Application\Modules\Sequode;

class Module {
    public static $module_registry_key = 'Sequode';
	public static function model(){
        $_o = (object)  array (
            'context' => 'sequode',
            'finder' => Collections::class,
            'collections' => Routes\Collections\Collections::class,
            'modeler' => Modeler::class,
            'components' => (object) array (
                'forms' => Components\Forms::class,
                'cards' => Components\Cards::class,
            ),
            'operations' => Operations::class,
            'operations_kit' => Kits\Operations::class,
            'xhr' => (object) array (
                'operations' => Routes\XHR\Operations::class,
                'forms' => Routes\XHR\Forms::class,
                'cards' => Routes\XHR\Cards::class
            ),
            'rest' => (object) array (
                'operations' => Routes\Rest\Operations::class,
                'downloads' => Routes\Rest\Downloads::class
            )
        );
        return $_o;
    }
}