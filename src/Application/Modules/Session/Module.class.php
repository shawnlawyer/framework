<?php

namespace Sequode\Application\Modules\Session;

class Module {
    public static $package = 'Session'; 
	public static function model(){
        $_o = (object)  array (
            'context' => 'token',
            'modeler' => Modeler::class,
            'operations' => Operations::class,
            'form_objects' => Components\Forms::class,
            'card_objects' => Components\Cards::class,
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