<?php

namespace Sequode\Model\Application;

class Runtime {
    
	public static function model($set = false){
        
        static $store;
        
		if(empty($store)){
            
            $store = false;
            
        }
        
        if($store === false && $set !== false){
            
            $store = forward_static_call_array(array($set ,'model'), array());
            
        }
        
        return $store;
        
    }
    
}