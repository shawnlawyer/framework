<?php

namespace Sequode\Application\Modules\Session\Traits\Operations;

use Sequode\Foundation\Hashes;

trait ORMModelCreate {
    
	public static function create($ip_address = null){
        
        $modeler = static::$modeler;
        
        $ip_address = ($ip_address == null)
            ? explode(',',((!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"]))[0]
            : $ip_address ;

        $modeler::create([
            "session_id" => Hashes::uniqueHash(),
            "session_data" => [],
            "session_start" => time(),
            "ip_address" => $ip_address,
            "name" => 'anon'
        ]);
        
        return $modeler::model();
        
    }
    
}