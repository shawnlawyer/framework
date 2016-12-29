<?php

namespace Sequode\Foundation;

abstract class Singleton{
    
    public static function getInstances($class, $dump=false) {
        
        static $instances = array();
        
        if (is_string($class) && $instances[$class] === null) {
            
            $instances[$class] = new $class;
            
        }
        
        return ($dump) ? $instances : ((!is_string($class)) ? null : $instances[$class]);
        
    }
    
}