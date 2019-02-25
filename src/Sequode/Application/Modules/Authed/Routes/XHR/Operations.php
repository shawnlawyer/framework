<?php

namespace Sequode\Application\Modules\Authed\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;

use Sequode\Application\Modules\Authed\Module;

class Operations {
    
    public static $module = Module::class;
    
	public static $merge = false;
	public static $routes = [
		'logout'
    ];
	public static $routes_to_methods = [
		'logout' => 'logout'
    ];
    public static function logout(){
        
        $module = static::$module;
        $operations = $module::model()->operations;
        forward_static_call_array([$operations, __FUNCTION__], []);
        $console_module =  ModuleRegistry::model()['Console'];

        forward_static_call_array([$console_module::model()->routes['http'], 'js'], [false]);
        
    }
}