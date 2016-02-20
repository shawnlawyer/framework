<?php

namespace Sequode\Application\Modules\BlockedIP;

class Module {
    public static $module_registry_key = 'BlockedIP';
	public static function model(){
        $_o = (object)  array (
            'context' => 'blockedip',
            'modeler' => Modeler::class,
            'operations' => Operations::class,
        );
		return $_o;
	}
}