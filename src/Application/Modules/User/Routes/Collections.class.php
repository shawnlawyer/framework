<?php

namespace Sequode\Application\Modules\User\Routes;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Collections{
    public static $package = 'User';
	public static $merge = true;
	public static $routes = array(
		'user_search'
	);
	public static $routes_to_methods = array(
		'user_search' => 'search'
	);
	public static function search(){
        $finder = ModuleRegistry::model(static::$package)->finder;
        $collection = ModuleRegistry::model(static::$package)->context . '_' . __FUNCTION__;
        $nodes = array();
        if(\\SQDE_Session::is($collection)){
            $_array = $finder::search(\\SQDE_Session::get($collection));
            foreach($_array as $_object){
                $nodes[] = '"'.$_object->id.'":{"id":"'.$_object->id.'","n":"'.$_object->username.'"}';
            }
        }
        echo '{'.implode(',', $nodes).'}';
        return;
	}
}