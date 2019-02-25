<?php

namespace Sequode\Application\Modules\BlockedIP;

use Sequode\Foundation\Hashes;
use Sequode\Model\Application\Configuration;

trait Authority {
    
    public static $modeler = Modeler::class;
    
	public static function isBlocked(){
        
        $modeler = static::$modeler;
        
        $ip_address = explode(',',((!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"]))[0];
        
        return ($modeler::exists($ip_address,'ip_address')) ? true : false ;
                
    }
    
}