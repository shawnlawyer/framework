<?php

namespace Sequode\Application\Modules\Token;

class Module {
    public static $package = 'Token';
	public static function model(){
        $_o = (object)  array (
            'context' => 'token',
            'modeler' => Modeler::class,
            'operations' => Operations::class,
            'components' => (object) array (
                'forms' => Components\Forms::class,
                'cards' => Components\Cards::class,
            ),
            'finder' => Collections::class,
            'collections' => Routes\Collections\Collections::class,
            'xhr' => (object) array (
                'operations' => Routes\XHR\Operations::class,
                'forms' => Routes\XHR\Forms::class,
                'cards' => Routes\XHR\Cards::class
            )
        );
		return $_o;
	}
}