<?php
namespace Sequode\Model\Module;

use Sequode\Foundation\Traits\StaticStoreTrait;

class Registry {
    
    use StaticStoreTrait;
    
	public static function model($set_class = false){
        
        static $class;
        
        if(empty($class)){
            
            $class = false;
            
        }
        
        if($set_class !== false){
            
            $class = $set_class;
            
        }
        
        $_o = [];
        
        if ($class !== false){
            
            $_o = forward_static_call_array([$class, 'model'], []);
            
            if($set_class !== false){
                
                self::load();
                
            }
        }
        
        return $_o;
        
    }
    
    public static function load(){
        
        self::clear();
        
        $array = self::model();
        
        foreach($array as $value){
            
            self::add($value);
            
        }
        
        return true;
        
    }
    
    public static function is($key){
        
        return self::container('is', $key);
        
    }
    
    public static function add($class){
        
        self::container('set', $class::$registry_key, $class);
        
        return true;
        
    }
    
    public static function clear(){
        
        self::container('clear');
        
        return true;
    }
            
    public static function module($key){
        
        return self::container('get', $key);
        
    }
    
    public static function modules(){
        
        return self::container('getAll');
        
    }
    
    public static function modulesContext(){
        
        $modules = self::container('getAll');
        
        $_o = [];
        foreach($modules as $key => $module){
            
            if(isset($module::model()->context)){
                
                $_o[$module::model()->context] = $key;
                
            }
            
        }
        
        return $_o;
        
    }
    
}