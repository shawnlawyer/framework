<?php

namespace Sequode\Application\Modules\Session\Routes\Collections;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Model\Module\Registry as ModuleRegistry;

class Collections{
    public static $package = 'Session';
	public static $merge = true;
	public static $routes = array(
		'session_search'
	);
	public static $routes_to_methods = array(
		'session_search' => 'search'
	);
	public static function search(){
        $finder = ModuleRegistry::model(static::$package)->finder;
        $collection = ModuleRegistry::model(static::$package)->context . '_' . __FUNCTION__;
        $nodes = array();
        if(SessionStore::is($collection)){
            $nodes[] = '"0":{"id":"0","n":"'.$collection.'"}';
            $_array = $finder::search(SessionStore::get($collection));
            foreach($_array as $_object){
                $nodes[] = '"'.$_object->id.'":{"id":"'.$_object->id.'","n":"'.$_object->username.'"}';
            }
        }
        echo '{'.implode(',', $nodes).'}';
        return;
	}
}