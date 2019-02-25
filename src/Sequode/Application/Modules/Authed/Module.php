<?php
namespace Sequode\Application\Modules\Authed;

use Sequode\Application\Modules\Account\Modeler;

class Module {
    public static $registry_key = 'Authed';
    public static $modeler = Modeler::class;
	public static function model(){
        $_o = (object)  [
            'context' => 'authed',
            'modeler' => Modeler::class,
            'components' => (object) [
                'cards' => Components\Cards::class,
            ],
            'operations' => Operations::class,
            'xhr' => (object) [
                'operations' => Routes\XHR\Operations::class
            ]
        ];
		return $_o;
	}
}