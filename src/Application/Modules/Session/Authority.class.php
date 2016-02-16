<?php

namespace Sequode\Application\Modules\Session;

class Authority {
    
	public static function isCookieValid(){
        
        return (isset($_COOKIE[Configuration::model()->sessions->cookie])) ? true : false ;
        
    }
    
}