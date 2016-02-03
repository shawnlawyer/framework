<?php

namespace Sequode\Application\Modules\User;

class Module {
    public static $package = 'User';
	public static function model(){
        $_o = (object)  array (
            'context' => 'user',
            'modeler' => Modeler::class,
            'card_objects' => Components\Cards::class,
            'form_objects' => Components\Forms::class,
            'operations' => Operations::class,
            'finder' => Collections::class,
            'collections' => Routes\Collections::class,
            'xhr' => (object) array (
                'operations' => Routes\XHR\Operations::class,
                'forms' => Routes\XHR\Forms::class,
                'cards' => Routes\XHR\Cards::class
            )
        );
		return $_o;
	}
}