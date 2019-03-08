<?php

namespace Sequode\Application\Modules\Session\Traits\Operations;

trait IsSetGetClearSessionStore {
    
    public static function is($key){
        
        return self::container('is', $key);
        
    }
    
    public static function get($key){
        
        return self::container('get', $key);
        
    }
    
    public static function getAll(){
        
        return self::container('getAll');
        
    }
    
    public static function clear(){
        
        self::container('clear');
        
        return true;
        
    }
    
    public static function set($key, $value = null, $save = true){
        
        $operations = static::$operations;
        
        self::container('set', $key, $value);
        
        if($save != false){
            
            $operations::save();
            
        }
        
        return true;
        
    }
    
    public static function setAll($value = null, $save = true){
        
        $operations = static::$operations;
        
        self::container('setAll', null, $value);
        
        if($save != false){
            
            $operations::save();
            
        }
        
        return true;
        
    }
    
}