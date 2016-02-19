<?php

namespace Sequode\Application\Modules\Package;

class Module {
    public static $package = 'Package';
	public static function model(){
        $_o = (object)  array (
            'context' => 'package',
            'finder' => Collections::class,
            'collections' => Routes\Collections\Collections::class,
            'modeler' => Modeler::class,
            'components' => (object) array (
                'forms' => Components\Forms::class,
                'cards' => Components\Cards::class,
            ),
            'operations' => Operations::class,
            'routes' => array (
                'downloads' => Routes\Rest\Downloads::class
            ),
            'xhr' => (object) array (
                'operations' => Routes\XHR\Operations::class,
                'forms' => Routes\XHR\Forms::class,
                'cards' => Routes\XHR\Cards::class
            ),
            'rest' => (object) array (
                'downloads' => Routes\Rest\Downloads::class
            )
        );
        return $_o;
    }
}