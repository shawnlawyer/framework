<?php
namespace Sequode\Application\Modules\Authed;

use Sequode\Application\Modules\Account\Modeler;

class Module {
    public static $modeler = Modeler::class;
	public static function model(){
        $_o = (object)  array (
            'context' => 'auth',
            'modeler' => Modeler::class,
            'components' => (object) array (
                'cards' => Components\Cards::class,
            ),
            'operations' => Operations::class,
            'xhr' => (object) array (
                'operations' => Routes\XHR\Operations::class
            )
        );
		return $_o;
	}
}