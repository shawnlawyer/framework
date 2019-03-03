<?php
namespace Sequode\Application\Modules\Authed;

use Sequode\Application\Modules\Account\Modeler;
use Sequode\Application\Modules\Traits\ModuleRoutesTrait;

class Module {

    use ModuleRoutesTrait;

    public static $registry_key = 'Authed';

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