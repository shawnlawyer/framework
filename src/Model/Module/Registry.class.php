<?php
namespace Sequode\Model\Module;

use Sequode\Foundation\Traits\StaticStoreTrait;

class Registry {
    
    use StaticStoreTrait;
    
    public static function is($key){
        
        return self::container('is', $key);
        
    }
    
    public static function add($class){
        
        self::container('set', $class::$registry_key, $class);
        
        return self::container('is', $class::$registry_key);
        
    }
    
    public static function model($key){
        
        $module = self::container('get', $key);
        
        return $module::model();
        
    }
    
    public static function models(){
        
        $modules = self::container('getAll');
        
        $_o = array();
        foreach($modules as $key => $module){
            
            $_o[$key] = $module::model(); 
            
        }
        
        return $_o;
        
    }
    
    public static function clear(){
        
        self::container('clear');
        
        return true;
    }
}