<?php
namespace Sequode\Model\Module;

use Sequode\Foundation\Traits\StaticStoreTrait;

class Registry {
    
    use StaticStoreTrait;
    
    public static function is($key){
        self::container('is', $key);
        return self::container('is', $key);
    }
    public static function add($class){
        self::container('set', $class::class, $class::model());
        return true;
    }
    public static function model($key){
        return self::container('get', $key);
    }
    public static function models(){
        return self::container('getAll');
    }
    
    public static function clear(){
        self::container('clear');
        return true;
    }
}