<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Application\Modules\Session\Modeler as SessionModeler;
use Sequode\Application\Modules\Session\Store as SessionStore;

trait ORMModelSignInTrait {
	
    public static function login($_model = null){
        
        $modeler = static::$modeler;
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $modeler::model()->last_sign_in = ($time === false) ? time() : $time;
        $modeler::model()->save();
        SessionModeler::model()->owner_id = $modeler::model()->id;
        SessionModeler::model()->name = $modeler::model()->email;
        SessionModeler::model()->save();
        SessionStore::set('owner_id', $modeler::model()->id, false);
        SessionStore::set('name', $modeler::model()->name, false);
        SessionStore::set('role_id', $modeler::model()->role_id);
        
        return $modeler::model();
        
    }
    
}