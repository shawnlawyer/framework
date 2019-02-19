<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Application\Modules\Session\Modeler as SessionModeler;
use Sequode\Application\Modules\Session\Operations as SessionOperations;
use Sequode\Application\Modules\Session\Store as SessionStore;

trait ORMModelSignInTrait {
	
    public static function login($_model = null){
        
        $modeler = static::$modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $modeler::model()->updateField(($time === false) ? time() : $time ,'last_sign_in');
        SessionModeler::model()->updateField($modeler::model()->email,'name');
        SessionStore::set('user_id', $modeler::model()->id, false);
        SessionStore::set('name', $modeler::model()->name, false);
        SessionStore::set('role_id', $modeler::model()->role_id, false);
        
        return $modeler::model();
        
    }
    
}