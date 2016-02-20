<?php

namespace Sequode\Application\Modules\Authed\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Operations {
    public static $module_registry_key = Sequode\Application\Modules\Authed\Module::class;
	public static $merge = false;
	public static $routes = array(
		'logout'
	);
	public static $routes_to_methods = array(
		'logout' => 'logout'
    );
    public static function logout(){
        $operations = ModuleRegistry::model(static::$module_registry_key)->operations;
        forward_static_call_array(array($operations,__FUNCTION__),array());
        return \Sequode\Application\Modules\Console\Routes\Routes::js(false);
    }
}