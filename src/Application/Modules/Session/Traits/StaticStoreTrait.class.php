<?php

namespace Sequode\Application\Modules\Session\Traits;

trait StaticStoreTrait {
    
    public static function container($mode, $key = null, $value = null) {
        
        static $store;
        
        if(!is_array($store)){
            
            $store = array();
            
        }
        switch($mode){
            
            case 'clear':
                $store = array();
                break;
            case 'is':
                return (array_key_exists($key, $store)) ? true : false ;
            case 'set':
                if($key != null){
                    
                    $store[$key] = $value;
                    
                }
                break;
            case 'get':
                if(array_key_exists($key, $store)){
                    
                    return $store[$key];
                    
                }
                break;
            case 'setAll':
                $store = $value;
                break;
            case 'getAll':
                return $store;
                break;
                
        }
        
    }
    
}