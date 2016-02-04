<?php

namespace Sequode\Application\Modules\BlockedIP;

class Module {
    public static $package = 'BlockedIP';
	public static function model(){
        $_o = (object)  array (
            'context' => 'blockedip',
            'modeler' => Modeler::class,
            'operations' => Operations::class,
        );
		return $_o;
	}
}