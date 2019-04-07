<?php

namespace Sequode\Application\Modules\BlockedIP;

use Sequode\Application\Modules\Traits\ModuleRoutesTrait;

class Module {

    use ModuleRoutesTrait;

    const Registry_Key = 'BlockedIP';

	public static function model(){
        $_o = (object)  [
            'context' => 'blocked_ip',
            'modeler' => Modeler::class,
            'operations' => Operations::class,
        ];
		return $_o;
	}
}