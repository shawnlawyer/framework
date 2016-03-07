<?php

namespace Sequode\Application\Modules\Authed\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;

use Sequode\Application\Modules\Authed\Module;

class Operations {
    
    public static $module = Module::class;
    
	public static $merge = false;
	public static $routes = array(
		'logout'
	);
	public static $routes_to_methods = array(
		'logout' => 'logout'
    );
    public static function logout(){
        
        $module = static::$module;
        $operations = $module::model()->operations;
        forward_static_call_array(array($operations, __FUNCTION__), array());
        forward_static_call_array(array(ModuleRegistry::model()['Console']->routes->http, 'js'), array());
        
    }
}