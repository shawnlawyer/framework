<?php

namespace Sequode\Component\Traits;

trait FetchObjectTrait {

    public static function fetchObject($class, $method, $parameters = null){
        
        return forward_static_call_array([$class, $method],($parameters == null) ? [] : $parameters);
        
	}
    
}