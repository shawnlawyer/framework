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
        \Sequode\Application\Modules\Session\Modeler::model()->updateField($modeler::model()->email,'username');
        \Sequode\Application\Modules\Session\Modeler::set('user_id', $modeler::model()->id, false);
        \Sequode\Application\Modules\Session\Modeler::set('username', $modeler::model()->username, false);
        \Sequode\Application\Modules\Session\Modeler::set('role_id', $modeler::model()->role_id, false);
		\Sequode\Application\Modules\Session\Modeler::set('console','Sequode', false);
        \Sequode\Application\Modules\Session\Modeler::save();
        return $modeler::model();
    }
    public static function load(){
        if(\Sequode\Application\Modules\Session\Modeler::isCookieValid() && \Sequode\Application\Modules\Session\Modeler::exists(\Sequode\Application\Modules\Session\Modeler::model()->session_id, 'session_id')){
            \Sequode\Application\Modules\Account\Modeler::exists(\Sequode\Application\Modules\Session\Modeler::get('user_id'),'id');
        }
    }
}