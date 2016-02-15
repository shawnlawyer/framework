<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

trait ORMModelSignInTrait {
	
    public static function login($_model = null){
        
        $modeler = static::$modeler;
        
        ($_model == null)
            ? forward_static_call_array(array($modeler,'model'),array())
            : forward_static_call_array(array($modeler,'model'),array($_model)) ;
            
        $modeler::model()->updateField(($time === false) ? time() : $time ,'last_sign_in');
        \Sequode\Application\Modules\Session\Modeler::model()->updateField($modeler::model()->email,'username');
        \Sequode\Application\Modules\Session\Modeler::set('user_id', $modeler::model()->id, false);
        \Sequode\Application\Modules\Session\Modeler::set('username', $modeler::model()->username, false);
        \Sequode\Application\Modules\Session\Modeler::set('role_id', $modeler::model()->role_id, false);
		\Sequode\Application\Modules\Session\Modeler::set('console','Sequode', false);
        \Sequode\Application\Modules\Session\Modeler::save();
        return $modeler::model();
    }
    
}