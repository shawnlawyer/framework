<?php

namespace Sequode\Application\Modules\Session\Traits\Operations;

use Sequode\Model\Application\Configuration;

trait SetGetSessionCookie {
   
    public static function setCookie(){
        
        $modeler = static::$modeler;
        $expire = time() + Configuration::model()->sessions->length;
        //if(!($_ENV['APP_ENV'] == 'local-dev' || preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/", Configuration::model()->sessions->domain))) {
            setcookie(Configuration::model()->sessions->cookie, $modeler::model()->session_id, $expire, Configuration::model()->sessions->path, '.'.Configuration::model()->sessions->domain);

            setcookie(Configuration::model()->sessions->cookie, $modeler::model()->session_id, $expire, Configuration::model()->sessions->path, '*.'.Configuration::model()->sessions->domain);

        //} else{
        //    setcookie(Configuration::model()->sessions->cookie, $modeler::model()->session_id, $expire, Configuration::model()->sessions->path, Configuration::model()->sessions->domain);

        //}

    }
    
	public static function getCookieValue(){
        
        return (!empty($_COOKIE[Configuration::model()->sessions->cookie])) ? $_COOKIE[Configuration::model()->sessions->cookie] : false;
        
    }
    
}