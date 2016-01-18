<?php
namespace Sequode\Patterns;

class Mason extends Singleton {

    public static function fetchObject($object_class, $object_method, $parameters = null){
        return forward_static_call_array(array($object_class,$object_method),($parameters == null) ? array() : $parameters);
	}
    
    public static function make(){
        return forward_static_call_array(array(get_called_class(), static::$mason),func_get_args());
	}
    
    public static function getInstance(){
		return Singleton::getInstances(get_class());   
    }
}