<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Application\Modules\Role\Modeler as RoleModeler;

trait ORMModelSetRoleTrait {
    
    public static function updateRole($role_model = null, $_model = null){
        
        $modeler = static::$modeler;
        
        if($role_model == null ){
            $role_model = RoleModeler::model();
        }

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $modeler::model()->updateField($role_model->id, 'role_id');
        
        return $modeler::model();
    }
    
}