<?php

namespace Sequode\Component\Traits;

class FetchObjectTrait {

    public static function fetchObject($class, $method, $parameters = null){
        
        return forward_static_call_array(array($class, $method),($parameters == null) ? array() : $parameters);
        
	}
    
}