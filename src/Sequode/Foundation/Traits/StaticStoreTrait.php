<?php

namespace Sequode\Foundation\Traits;

trait StaticStoreTrait {
    
    public static function container($mode, $key = null, $value = null) {
        
        static $store;
        
        if(!is_array($store)){
            
            $store = [];
            
        }
        
        switch($mode){
            
            case 'clear':
                $store = [];
                return;
            case 'is':
                return (array_key_exists($key, $store)) ? true : false ;
            case 'set':
                if($key != null){
                    
                    $store[$key] = $value;
                    
                }
                return;
            case 'get':
                if(array_key_exists($key, $store)){
                    
                    return $store[$key];
                    
                }
                return;
            case 'setAll':
                $store = $value;
                return;
            case 'getAll':
                return $store;
                
        }
        
    }
    
}