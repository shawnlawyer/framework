<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelSetRoleTrait {
    
    public static function updateRole($role_model = null, $_model = null){
        if($role_model == null ){ $role_model = \SQDE_Role::model(); }
        
        ($_model == null)
            ? forward_static_call_array(array(static::$modeler,'model'),array())
            : forward_static_call_array(array(static::$modeler,'model'),array($_model)) ;
            
        return static::$modeler::model();
    }
    
}