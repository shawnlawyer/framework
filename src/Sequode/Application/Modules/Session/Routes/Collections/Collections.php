<?php

namespace Sequode\Application\Modules\Session\Routes\Collections;

use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\Application\Modules\Session\Module;
    
class Collections{
    
    public static $module = Module::class;
    
	public static $merge = false;
	public static $routes = [
		'session_search'
    ];
	public static $routes_to_methods = [
		'session_search' => 'search'
    ];
	public static function search(){
        
        $module = static::$module;
        
        $finder = $module::model()->finder;
        $collection = $module::model()->context . '_' . __FUNCTION__;
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