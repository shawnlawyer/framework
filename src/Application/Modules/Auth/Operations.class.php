<?php

namespace Sequode\Application\Modules\Auth;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Operations {
    public static $package = 'Auth';
	public static function uniqueHash($seed='',$prefix='SQDE'){
		$time = explode(' ', microtime());
        $time = $time[0] + $time[1];
		return $prefix.md5($time.$seed);
	}
	public static function generateHash($text, $salt = null){
        if ($salt === null){
            $salt = substr(md5(uniqid(rand(), true)), 0, 25);
        }else{
            $salt = substr($salt, 0, 25);
        }
        return $salt . sha1($salt . $text);
    }
    public static function login($_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->updateField(($time === false) ? time() : $time ,'last_sign_in');
        \SQDE_Session::model()->updateField($modeler::model()->email,'username');
        \SQDE_Session::set('user_id', $modeler::model()->id, false);
        \SQDE_Session::set('username', $modeler::model()->username, false);
        \SQDE_Session::set('role_id', $modeler::model()->role_id, false);
		\SQDE_Session::set('console','Sequode', false);
        \SQDE_Session::save();
        return $modeler::model();
    }
    public static function load(){
        if(\SQDE_Session::isCookieValid() && \SQDE_Session::exists(\SQDE_Session::model()->session_id, 'session_id')){
            \Sequode\Application\Modules\Auth\Modeler::exists(SQDE_Session::get('user_id'),'id');
        }
    }
}