<?php

namespace Sequode\Application\Modules\User\Routes\Collectons;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;

use Sequode\Application\Modules\User\Module;

class Collections{
    
    public static $module = Module::class;
    
	public static $merge = false;
	public static $routes = array(
		'user_search'
	);
	public static $routes_to_methods = array(
		'user_search' => 'search'
	);
	public static function search(){
        
        $module = static::$module;
        $finder = $module::model()->finder;
        $collection = 'user_search';
        
        $nodes = array();
        if(SessionStore::is($collection)){
            $_array = $finder::search(SessionStore::get($collection));
            foreach($_array as $_object){
                $nodes[] = '"'.$_object->id.'":{"id":"'.$_object->id.'","n":"'.$_object->username.'"}';
            }
        }
        
        echo '{'.implode(',', $nodes).'}';
        
        return;
        
	}
}