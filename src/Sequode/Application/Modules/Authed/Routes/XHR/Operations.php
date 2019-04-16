<?php

namespace Sequode\Application\Modules\Authed\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;

use Sequode\Application\Modules\Authed\Module;

class Operations {

    const Module = Module::class;
    
	public static $merge = false;

	public static $routes = [
		'logout'
    ];

    const Routes = [
		'logout'
    ];

	public static $routes_to_methods = [
		'logout' => 'logout'
    ];

    public static function logout(){

        extract((static::Module)::variables());

        forward_static_call_array([$operations, __FUNCTION__], []);

        return implode(' ', [
            'new Console();'
        ]);
        
    }
}