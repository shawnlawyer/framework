<?php

namespace Sequode\Application\Modules\Account;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Operations {
    
    public static $package = 'Account';
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
	public static function getModelersModelCount($_modeler, $_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $_models = new $_modeler::$model;
        $where = array();
        $where[] = array('field'=>'owner_id','operator'=>'=','value'=>$modeler::model()->id);
        $_models->getCount($where, 'id');
        return intval($_models->results_count);
	}
	public static function getOwnedModels($package, $_model = null, $fields='id'){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $package_modeler = ModuleRegistry::model($package)->modeler;
        $package_model = new $package_modeler::$model;
        $where = array();
        $where[] = array('field'=>'owner_id','operator'=>'=','value'=>$modeler::model()->id);
        $package_model->getAll($where, $fields);
        return $package_model;
	}
    public static function updatePassword($password, $_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->updateField(self::generateHash($password),'password');
        return $modeler::model();
    }
    public static function emptySequodeFavorites($_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->updateField('[]','sequode_favorites');
        return $modeler::model();
    }
    public static function addToSequodeFavorites($sequode_model = null, $_model = null){
        if($sequode_model != null ){ \Sequode\Application\Modules\Sequode\Modeler::model($sequode_model); }
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
		$palette = json_decode($_model->sequode_favorites);
		$palette[] = \Sequode\Application\Modules\Sequode\Modeler::model()->id;
        $modeler::model()->updateField(json_encode(array_unique($palette)),'sequode_favorites');
        return $modeler::model();
    }
    public static function removeFromSequodeFavorites($sequode_model = null, $_model = null){
        if($sequode_model != null ){ \Sequode\Application\Modules\Sequode\Modeler::model($sequode_model); }
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
		$palette = json_decode($modeler::model()->sequode_favorites);
        $array = array();
		foreach($palette as $value){
			if(intval($value) != \Sequode\Application\Modules\Sequode\Modeler::model()->id){
				$array[] = $value;
			}
		}
        $modeler::model()->updateField(json_encode($array),'sequode_favorites');
        return $modeler::model();
    }
    public static function updateEmail($email, $_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->updateField($email,'email');
        return $modeler::model();
    }
    /*
    public static function updateName($username, $_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->updateField($username, 'username');
        return $modeler::model();
    }
    
    */
}