<?php

namespace Sequode\Application\Modules\Session\Traits\Operations;

use Sequode\Foundation\Hashes;

trait ManageSessionStore {
    
	public static function start($auto_create = true){
        
        $modeler = static::$modeler;
        
        $ip_address = explode(',',((!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"]))[0];
        if(!\Sequode\Application\Modules\BlockedIP\Modeler::exists($ip_address,'ip_address')){
            
            $ip_pieces = explode('.', $ip_address);
            $auto_block_ips = array('180','111','88','31');
            
            if(in_array($ip_pieces[0], $auto_block_ips)){
                
                \Sequode\Application\Modules\BlockedIP\Modeler::model()->create($ip_address);
            }
            
        }
        
        if(isset(\Sequode\Application\Modules\BlockedIP\Modeler::model()->id)){
            
            die('Sequo De');
            
        }
        
        if($modeler::exists(self::getCookieValue()) && $modeler::model()->ip_address == $ip_address /* &&  time() < self::model()->session_start + 86400 */){
            
            self::load();
            self::set('history', array_merge(self::get('history'), array(substr($_SERVER['REQUEST_URI'], 0, 25))));
            
        }elseif($auto_create == true && $_SERVER['HTTP_HOST'] == Configuration::model()->sessions->create_domain){
            
            self::create($ip_address);
            self::set('history', array(substr($_SERVER['REQUEST_URI'], 0, 25)));
            self::setCookie();
            
        }
        
    }
    
	public static function end(){
        
        $modeler = static::$modeler;
        
        $modeler::model()->updateField(Hashes::uniqueHash(),'session_id');
        /*$modeler::model()->updateField(time() - 86400,'session_start');*/
        self::clear();
        
        $modeler::model()::model(null);
        
    }
    
	public static function destroy(){
        
        $modeler = static::$modeler;
        
        $modeler::model()->delete($modeler::model()->id);
        
        self::clear();
        
        $modeler::model(null);
        
    }
    
	public static function save(){
        
        $modeler = static::$modeler;
        
        $modeler::model()->updateField(serialize($modeler::container('getAll')),'session_data');
        
    }
    
	public static function load(){
        
        $modeler = static::$modeler;
        
        $modeler::container('setAll', null, unserialize($modeler::model()->session_data));
        
    }
    
    public static function is($key){
        
        $modeler = static::$modeler;
        
        return $modeler::container('is', $key);
        
    }
    
    public static function get($key){
        
        $modeler = static::$modeler;
        
        return $modeler::container('get', $key);
        
    }
    
    public static function getAll(){
        
        $modeler = static::$modeler;
        
        return $modeler::container('getAll');
        
    }
    
    public static function clear(){
        
        $modeler = static::$modeler;
        
        $modeler::container('clear');
        
        return true;
        
    }
    
    public static function set($key, $value = null, $save = true){
        
        $modeler = static::$modeler;
        
        $modeler::container('set', $key, $value);
        
        if($save != false){
            
            self::save();
            
        }
        
        return true;
        
    }
    
    public static function setAll($value = null, $save = true){
        
        $modeler = static::$modeler;
        
        $modeler::container('setAll', null, $value);
        
        if($save != false){
            
            self::save();
            
        }
        
        return true;
        
    }
    
}