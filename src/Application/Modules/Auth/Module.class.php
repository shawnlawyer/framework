<?php

namespace Sequode\Application\Modules\Auth;

use Sequode\Application\Modules\Account\Modeler;

class Module {
    public static $registry_key = 'Auth';
    public static $modeler = Modeler::class;
	public static function model(){
        $_o = (object)  array (
            'context' => 'auth',
            'modeler' => Modeler::class,
            'components' => (object) array (
                'forms' => Components\Forms::class,
                'cards' => Components\Cards::class,
                'dialogs' => Components\Dialogs::class,
            ),
            'operations' => Operations::class,
            'xhr' => (object) array (
                'operations' => Routes\XHR\Operations::class,
                'cards' => Routes\XHR\Cards::class,
            )
        );
		return $_o;
	}
}