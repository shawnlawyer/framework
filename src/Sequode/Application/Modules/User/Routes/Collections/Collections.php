<?php

namespace Sequode\Application\Modules\User\Routes\Collections;

use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\Application\Modules\User\Module;

class Collections{
    
    const Module = Module::class;

	public static $merge = false;

	public static $routes = [
		'user_search'
    ];

	public static $routes_to_methods = [
		'user_search' => 'search'
    ];

	public static function search(){

        extract((static::Module)::variables());

        $collection = 'user_search';
        
        $nodes = [];

        if(SessionStore::is($collection)){

            $_array = $finder::search(SessionStore::get($collection));

            foreach($_array as $_object){

                $nodes[] = '"'.$_object->id.'":{"id":"'.$_object->id.'","n":"'.$_object->name.'"}';

            }

        }
        
        echo '{'.implode(',', $nodes).'}';
        
        return;
        
	}
}