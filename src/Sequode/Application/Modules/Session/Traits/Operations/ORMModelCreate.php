<?php

namespace Sequode\Application\Modules\Session\Traits\Operations;

use Sequode\Foundation\Hashes;

trait ORMModelCreate {
    
	public static function create($ip_address = null){
        
        $modeler = static::$modeler;
        
        $ip_address = ($ip_address == null)
            ? explode(',',((!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"]))[0]
            : $ip_address ;
        $modeler::model()->create();
        $modeler::model()->session_id = Hashes::uniqueHash();
        $modeler::model()->session_data = serialize([]);
        $modeler::model()->session_start = time();
        $modeler::model()->ip_address = $ip_address;
        $modeler::model()->name('anon', 'name');
        $modeler::model()->save();
        
        return $modeler::model();
        
    }
    
}