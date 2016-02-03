<?php
namespace Sequode\Application\Modules\User;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Operations {

    public static $package = 'User';
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
	public static function getModelersModelCount($_modeler, $_model){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $_models = new \SQDE_Package::$model;
        $where = array();
        $where[] = array('field'=>'owner_id','operator'=>'=','value'=>$modeler::model()->id);
        $_models->getCount($where, 'id');
        return intval($_models->results_count);
	}
	public static function getOwnedModels($package, $_model, $fields='id'){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $package_modeler = ModuleRegistry::model($package)->modeler;
        $package_model = new $package_modeler::$model;
        $where = array();
        $where[] = array('field'=>'owner_id','operator'=>'=','value'=>$modeler::model()->id);
        $package_model->getAll($where, $fields);
        return $package_model;
	}
    public static function updateActive($active = 0, $_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        if($_model != null ){ $modeler::model($_model); }
        $active = (intval($active) == 1) ? 1 : 0;
        $modeler::model()->updateField($active,'active');
        return $modeler::model();
    }
    public static function register($username, $password, $email, $_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->create($username,self::generateHash($password),$email);
        $modeler::exists($modeler::model()->id, 'id');
        $modeler::model(self::unactive());
        return $modeler::model();
    }
    public static function updateLastSignIn($time=false, $_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->updateField(($time === false) ? time() : $time ,'last_sign_in');
        return $modeler::model();
    }
    public static function updateEmail($email, $_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->updateField($email,'email');
        return $modeler::model();
    }
    public static function updatePassword($password, $_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->updateField(self::generateHash($password),'password');
        return $modeler::model();
    }
    public static function updateName($username, $_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->updateField($username, 'username');
        return $modeler::model();
    }
    public static function emptySequodeFavorites($_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->updateField('[]','sequode_favorites');
        return $modeler::model();
    }
    public static function addToSequodeFavorites($sequode_model = null, $_model = null){
        if($sequode_model != null ){ \SQDE_Sequode::model($sequode_model); }
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
		$palette = json_decode($_model->sequode_favorites);
		$palette[] = \SQDE_Sequode::model()->id;
        $modeler::model()->updateField(json_encode(array_unique($palette)),'sequode_favorites');
        return $modeler::model();
    }
    public static function removeFromSequodeFavorites($sequode_model = null, $_model = null){
        if($sequode_model != null ){ \SQDE_Sequode::model($sequode_model); }
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
		$palette = json_decode($modeler::model()->sequode_favorites);
        $array = array();
		foreach($palette as $value){
			if(intval($value) != \SQDE_Sequode::model()->id){
				$array[] = $value;
			}
		}
        $modeler::model()->updateField(json_encode($array),'sequode_favorites');
        return $modeler::model();
    }
    
    public static function login($_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        \SQDE_AuthenticatedUser::model($modeler::model());
        \SQDE_Session::model()->updateField($modeler::model()->username,'username');
        \SQDE_Session::set('user_id', $modeler::model()->id, false);
        \SQDE_Session::set('username', $modeler::model()->username, false);
        \SQDE_Session::set('role_id', $modeler::model()->role_id, false);
        \SQDE_Session::save();
        return \SQDE_AuthenticatedUser::model();
    }
    public static function newUser(){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $modeler::model()->create(substr(\SQDE_Session::uniqueHash(),0,15), \SQDE_Session::uniqueHash(), substr(\SQDE_Session::uniqueHash(),0,15));
        $modeler::exists($modeler::model()->id, 'id');
        $modeler::model()->updateField('[]','sequode_favorites');
        $modeler::model()->updateField('100','role_id');
        $modeler::model()->updateField('33','allowed_sequode_count');
        $modeler::model()->updateField('1','active');
        return $modeler::model();
    }
    public static function newGuest(){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $modeler::model()->create(substr(\SQDE_Session::uniqueHash(),0,15), \SQDE_Session::uniqueHash(), substr(\SQDE_Session::uniqueHash(),0,15));
        $modeler::exists($modeler::model()->id, 'id');
        $modeler::model()->updateField('[]','sequode_favorites');
        $modeler::model()->updateField('101','role_id');
        $modeler::model()->updateField('5','allowed_sequode_count');
        $modeler::model()->updateField('1','active');
        return $modeler::model();
    }
    public static function delete($_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        if(!($_model->id != 1 && $_model->id != 2 && $_model->role_id > 99)){
            return \SQDE_User::model($_model);
        }
        $sequodes_model = self::getSequodesModelOfAllSequencedSequodes($_model);
        foreach($sequodes_model->all as $object){
            $sequodes_model->delete($object->id);
        }
        $modeler::model()->delete($_model->id);
        return $modeler::model();
    }
    public static function updateRole($role_model = null, $_model = null){
        if($role_model == null ){ $role_model = \SQDE_Role::model(); }
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        ($_model == null) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'),array($_model));
        $modeler::model()->updateField($role_model->id,'role_id');
        return $modeler::model();
    }

}