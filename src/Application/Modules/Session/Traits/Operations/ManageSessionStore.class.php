<?php

namespace Sequode\Application\Modules\Session\Traits\Operations;

use Sequode\Foundation\Hashes;
use Sequode\Model\Application\Configuration;

trait ManageSessionStore {
    
	public static function start($auto_create = true){
        
        $modeler = static::$modeler;
        
        $ip_address = explode(',',((!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"]))[0];
        
        if($modeler::exists(self::getCookieValue()) && $modeler::model()->ip_address == $ip_address /* &&  time() < self::model()->session_start + 86400 */){
            
            self::load();
        
        }elseif($auto_create == true && $_SERVER['HTTP_HOST'] == Configuration::model()->sessions->create_domain){
            
            self::create($ip_address);
            self::setCookie();
            
        }
        
    }
    
	public static function end(){
        
        $modeler = static::$modeler;
        
        $modeler::model()->updateField(Hashes::uniqueHash(),'session_id');
        /*$modeler::model()->updateField(time() - 86400,'session_start');*/
        $store::clear();
        
        $modeler::model(null);
        
    }
    
	public static function destroy(){
        
        $modeler = static::$modeler;
        $store = static::$store;
        
        $modeler::model()->delete($modeler::model()->id);
        
        $store::clear();
        
        $modeler::model(null);
        
    }
    
	public static function load(){
        
        $modeler = static::$modeler;
        $store = static::$store;
        
        $store::container('setAll', null, unserialize($modeler::model()->session_data));
        
    }
    
	public static function save(){
        
        $modeler = static::$modeler;
        $store = static::$store;
        
        $modeler::model()->updateField(serialize($store::container('getAll')),'session_data');
        
    }
    
}