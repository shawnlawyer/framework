<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Model\Application\Configuration;

class Modeler extends \Sequode\Patterns\Modeler {
    
    public static $model = Sequode\Application\Models\ORM\Sessions::class;
    
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
    public static function is($key){
        self::container('is', $key);
        return self::container('is', $key);
    }
    public static function get($key){
        return self::container('get', $key);
    }
    public static function getAll(){
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
	public static function create($ip_address = null){
        if($ip_address == null){$ip_address = explode(',',((!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"]))[0];}
        self::model()->create();
        self::model()->exists(self::model()->id,'id');
        self::model()->updateField(self::uniqueHash(),'session_id');
        self::model()->updateField(serialize(array()),'session_data');
        self::model()->updateField(time(),'session_start');
        $ip_address = (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
        self::model()->updateField($ip_address, 'ip_address');
        self::model()->updateField('anon','username');
    }
	public static function start($auto_create = true){
        $ip_address = explode(',',((!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"]))[0];
        if(!\Sequode\Application\Modules\BlockedIP\Modeler::exists($ip_address,'ip_address')){
            $ip_pieces = explode('.', $ip_address);
            $auto_block_ips = array('180','111','88','31');
            if(in_array($ip_pieces[0], $auto_block_ips)){
                \Sequode\Application\Modules\BlockedIP\Modeler::model()->create($ip_address);
            }
        }
        if(isset(\Sequode\Application\Modules\BlockedIP\Modeler::model()->id)){
            die('Sequo De');
        }
        if(self::exists(self::getCookieValue()) && self::model()->ip_address == $ip_address /* &&  time() < self::model()->session_start + 86400 */){
            self::load();
            \Sequode\Application\Modules\Session\Modeler::set('history', array_merge(self::get('history'), array(substr($_SERVER['REQUEST_URI'], 0, 25))));
        }elseif($auto_create == true && $_SERVER['HTTP_HOST'] == Configuration::model()->sessions->create_domain){
            self::create($ip_address);
            \Sequode\Application\Modules\Session\Modeler::set('history', array(substr($_SERVER['REQUEST_URI'], 0, 25)));
            self::setCookie();
        }
    }
	public static function exists($value,$by='session_id'){
        return (self::model()->exists($value, $by)) ? true : false ;
    }
	public static function id(){
       return self::model()->session_id;
    }
	public static function end(){
        self::model()->updateField(self::uniqueHash(),'session_id');
        /*self::model()->updateField(time() - 86400,'session_start');*/
        self::clear();
        self::model(null);
    }
	public static function destroy(){
        self::model()->delete(self::model()->id);
        self::clear();
        self::model(null);
    }
	public static function save(){
        self::model()->updateField(serialize(self::getAll()),'session_data');
    }
	public static function load(){
        self::setAll(unserialize(self::model()->session_data),false);
    }
	public static function setCookie(){
        $expire = time()+Configuration::model()->sessions->length;
        setcookie(Configuration::model()->sessions->cookie, self::id(), $expire, Configuration::model()->sessions->path, '.'.Configuration::model()->sessions->domain);
        setcookie(Configuration::model()->sessions->cookie, self::id(), $expire, Configuration::model()->sessions->path, '*.'.Configuration::model()->sessions->domain);
    }
	public static function getCookieValue(){
        return (self::isCookieValid()) ? $_COOKIE[Configuration::model()->sessions->cookie] : false;
    }
	public static function isCookieValid(){
        return (isset($_COOKIE[Configuration::model()->sessions->cookie])) ? true : false ;
    }
    public static function set($key, $value = null, $save = true){
        self::container('set', $key, $value);
        if($save != false){
            self::save();
        }
        return true;
    }
    public static function setAll($value = null, $save = true){
        self::container('setAll', null, $value);
        if($save != false){
            self::save();
        }
        return true;
    }
}