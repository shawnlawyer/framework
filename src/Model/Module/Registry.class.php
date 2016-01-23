<?php
namespace Sequode\Model\Module;

class Registry {
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
            case 'add':
                if($key != null){
                    $store[$key] = $value;
                }
                break;
            case 'get':
                if(array_key_exists($key, $store)){
                    return $store[$key];
                }
                break;
            case 'getAll':
                return $store;
                break;
        }
    }
    public static function is($key){
        self::container('is', $key);
        return self::container('is', $key);
    }
    public static function add($package_class){
        self::container('add', $package_class::$package, $package_class::model());
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
	public static function uniqueHash($seed='',$prefix='SQDE'){
		$time = explode(' ', microtime());
        $time = $time[0] + $time[1];
		return $prefix.md5($time.$seed);
	}
}