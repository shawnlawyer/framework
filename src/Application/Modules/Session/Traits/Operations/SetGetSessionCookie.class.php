<?php

namespace Sequode\Application\Modules\Session\Traits\Operations;

use Sequode\Model\Application\Configuration;

trait ManageSessionCookie {
   
    public static function setCookie(){
        
        $modeler = static::$modeler;
        
        $expire = time() + Configuration::model()->sessions->length;
        
        setcookie(Configuration::model()->sessions->cookie, $modeler::model()->session_id, $expire, Configuration::model()->sessions->path, '.'.Configuration::model()->sessions->domain);
        
        setcookie(Configuration::model()->sessions->cookie, $modeler::model()->session_id, $expire, Configuration::model()->sessions->path, '*.'.Configuration::model()->sessions->domain);
        
    }
    
	public static function getCookieValue(){
        
        return (!empty($_COOKIE[Configuration::model()->sessions->cookie])) ? $_COOKIE[Configuration::model()->sessions->cookie] : false;
        
    }
    
}