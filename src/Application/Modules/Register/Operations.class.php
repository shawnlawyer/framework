<?php

namespace Sequode\Application\Modules\Register;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Operations {
    public static $package = 'Register';
	public static function uniqueHash($seed='',$prefix='SQDE'){
		$time = explode(' ', microtime());
        $time = $time[0] + $time[1];
		return $prefix.md5($time.$seed);
	}
	public static function generateHash($text, $salt = null){
        $salt = ($salt === null) ? substr(md5(uniqid(rand(), true)), 0, 25) : substr($salt, 0, 25);
        return $salt . sha1($salt . $text);
    }
    public static function signup($email, $password){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $modeler::model()->create(self::generateHash($email),self::generateHash($password),$email);
        $modeler::model()->updateField('1','active');
        $modeler::model()->updateField('1','verified');
        $modeler::model()->updateField('[]','sequode_favorites');
        $modeler::model()->updateField('100','role_id');
        $modeler::model()->updateField('33','allowed_sequode_count');
        $modeler::exists($modeler::model()->id, 'id');
        return $modeler::model();
    }
}