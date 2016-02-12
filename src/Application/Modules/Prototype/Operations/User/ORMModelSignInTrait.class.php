<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelSignInTrait {
	
    public static function login($_model = null){
        
        ($_model == null)
            ? forward_static_call_array(array(static::$modeler,'model'),array())
            : forward_static_call_array(array(static::$modeler,'model'),array($_model)) ;
            
        static::$modeler::model()->updateField(($time === false) ? time() : $time ,'last_sign_in');
        \Sequode\Application\Modules\Session\Modeler::model()->updateField(static::$modeler::model()->email,'username');
        \Sequode\Application\Modules\Session\Modeler::set('user_id', static::$modeler::model()->id, false);
        \Sequode\Application\Modules\Session\Modeler::set('username', static::$modeler::model()->username, false);
        \Sequode\Application\Modules\Session\Modeler::set('role_id', static::$modeler::model()->role_id, false);
		\Sequode\Application\Modules\Session\Modeler::set('console','Sequode', false);
        \Sequode\Application\Modules\Session\Modeler::save();
        return $modeler::model();
    }
    
}