<?php

namespace Sequode\Application\Modules\User;

use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Foundation\Hashes;
use Sequode\Application\Modules\Sequode\Authority as SequodeAuthority;

class Authority {
    
    public static $modeler = Modeler::class;

    public static function isOwner($test_model, $_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return (isset($_model->id) && isset($test_model->owner_id) &&  $test_model->owner_id == $_model->id) ? true : false;
    }
    public static function isSystemOwner($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return (isset($_model->role_id) && $_model->role_id == 0) ? true : false;
    }
    public static function canCreate($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        $all_models = new $modeler::$model;
        $where[] = ['field'=>'owner_id','operator'=>'=','value'=>$_model->id];
        $all_models->getCount($where);
        $count = $all_models->results_count;
        unset($all_models);
        return ($count < $_model->allowed_sequode_count || ($_model->role_id <= 100 && $count <= 101)) ? true : false;
    }
    public static function canEdit($test_model,  $_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return (self::isOwner($test_model, $_model) || self::isSystemOwner($_model)) ? true : false;
    }
    public static function canCopy($test_model,  $_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return (self::isOwner($test_model, $_model) || self::isSystemOwner($_model)) ? true : false;
    }
    public static function canDelete($test_model,  $_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return (self::isOwner($test_model, $_model) || self::isSystemOwner($_model)) ? true : false;
    }
    public static function canShare($test_model,  $_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return (self::isOwner($test_model, $_model) || self::isSystemOwner($_model)) ? true : false;
    }
    public static function canRun($test_model,  $_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return (
            SequodeAuthority::isShared($test_model)
            || self::isOwner($test_model, $_model)
            || self::isSystemOwner($_model)
        ) ? true : false;
    }
    public static function canView($test_model, $_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return (
            SequodeAuthority::isShared($test_model)
            || self::isOwner($test_model, $_model)
            || self::isSystemOwner($_model)
        ) ? true : false;
    }
    public static function canRenameTo($name, $test_model, $_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        $model = $test_model;
        $all_models = new $model;
        $where[] = ['field'=>'owner_id','operator'=>'=','value'=>$_model->id];
        $where[] = ['field'=>'name','operator'=>'=','value'=>$name];
        $where[] = ['field'=>'id','operator'=>'!=','value'=>$test_model->id];
        $all_models->getCount($where);
        $count = $all_models->results_count;
        unset($all_models);
        return ($count == 0) ? true : false;
    }
    public static function isActive($_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return (isset($_model->active) && $_model->active == 1) ? true : false;
    }
    public static function isPassword($password, $_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return (Hashes::generateHash($password, $_model->password) == $_model->password) ? true : false;
    }
    public static function isSecurePassword($password){
        return ( strlen($password) >= 8 &&  strlen($password) <= 100 && preg_match("#[0-9]+#", $password) && preg_match("#[a-z]+#", $password) && preg_match("#[A-Z]+#", $password) && preg_match("#\W+#", $password) ) ? true : false;
    }
    public static function isInSequodeFavorites($test_model, $_model = null){
        $modeler = static::$modeler;
        if($_model == null ){ $_model = $modeler::model(); }
        return (in_array($test_model->id, json_decode($_model->sequode_favorites))) ? true : false;
    }
    public static function isAnEmailAddress($email){
        return (filter_var($email,FILTER_VALIDATE_EMAIL) === false) ? false : true;
    }
}