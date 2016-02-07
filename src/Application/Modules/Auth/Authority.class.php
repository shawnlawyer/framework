<?php

namespace Sequode\Application\Modules\Auth;

class Authority {
    
    public static function isAuthenticated($user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        return (self::isActive($user_model) || self::isSystemOwner($user_model)) ? true : false;
    }
    public static function isOwner(&$_model, $user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        return (isset($user_model->id) && isset($_model->owner_id) &&  $_model->owner_id == $user_model->id) ? true : false;
    }
    public static function isSequodeOwner($sequode_model = null, $user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return (isset($user_model->id) && isset($sequode_model->owner_id) &&  $sequode_model->owner_id == $user_model->id) ? true : false;
    }
    public static function isMachineOwner($machine_model = null, $user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        if($machine_model == null ){ $machine_model = SQDE_Machine::model(); }
        return (isset($user_model->id) && isset($machine_model->owner_id) &&  $machine_model->owner_id == $user_model->id) ? true : false;
    }
    public static function isSystemOwner($user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        return (isset($user_model->role_id) && $user_model->role_id == 0) ? true : false;
    }
    public static function canCreate($user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        $all_models = new \Sequode\Application\Modules\Sequode\Modeler::$model;
        $where[] = array('field'=>'owner_id','operator'=>'=','value'=>$user_model->id);
        $all_models->getCount($where);
        $count = $all_models->results_count;
        unset($all_models);
        return ($count < $user_model->allowed_sequode_count || ($user_model->role_id <= 100 && $count <= 101)) ? true : false;
    }
    public static function canEdit($sequode_model = null,  $user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return (self::isSequodeOwner($sequode_model, $user_model) || self::isSystemOwner($user_model)) ? true : false;
    }
    public static function canCopy($sequode_model = null,  $user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return (self::isSequodeOwner($sequode_model, $user_model) || self::isSystemOwner($user_model)) ? true : false;
    }
    public static function canDelete($sequode_model = null,  $user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return (self::isSequodeOwner($sequode_model, $user_model) || self::isSystemOwner($user_model)) ? true : false;
    }
    public static function canShare($sequode_model = null,  $user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return (self::isSequodeOwner($sequode_model, $user_model) || self::isSystemOwner($user_model)) ? true : false;
    }
    public static function canRun($sequode_model = null,  $user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return (
            \Sequode\Application\Modules\Sequode\Authority::isShared($sequode_model)
            || self::isSequodeOwner($sequode_model, $user_model)
            || self::isSystemOwner($user_model)
        ) ? true : false;
    }
    public static function canView($sequode_model = null, $user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return (
            \Sequode\Application\Modules\Sequode\Authority::isShared($sequode_model)
            || self::isSequodeOwner($sequode_model, $user_model)
            || self::isSystemOwner($user_model)
        ) ? true : false;
    }
    public static function canRename($name, $sequode_model = null, $user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        $all_models = new \Sequode\Application\Modules\Sequode\Modeler::$model;
        $where[] = array('field'=>'owner_id','operator'=>'=','value'=>$user_model->id);
        $where[] = array('field'=>'name','operator'=>'=','value'=>$name);
        $where[] = array('field'=>'id','operator'=>'!=','value'=>$sequode_model->id);
        $all_models->getCount($where);
        $count = $all_models->results_count;
        unset($all_models);
        return ($count == 0) ? true : false;
    }
    public static function isActive($user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        return ($user_model->active == 1) ? true : false;
    }
    public static function isPassword($password, $user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        return (\Sequode\Application\Modules\Account\Operations::generateHash($password, $user_model->password) == $user_model->password) ? true : false;
    }
    public static function isSecurePassword($password){
        return ( strlen($password) >= 8 &&  strlen($password) <= 100 && preg_match("#[0-9]+#", $password) && preg_match("#[a-z]+#", $password) && preg_match("#[A-Z]+#", $password) && preg_match("#\W+#", $password) ) ? true : false;
    }
    public static function isInSequodeFavorites($sequode_model = null, $user_model = null){
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Auth\Modeler::model(); }
        if($sequode_model == null ){ $sequode_model = \Sequode\Application\Modules\Sequode\Modeler::model(); }
        return (in_array($sequode_model->id, json_decode($user_model->sequode_favorites))) ? true : false;
    }
    public static function isAnEmailAddress($email){
        return (filter_var($email,FILTER_VALIDATE_EMAIL) === false) ? false : true;
    }
}