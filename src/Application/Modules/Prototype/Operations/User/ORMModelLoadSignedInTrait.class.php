<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelLoadSignedInTrait {
	
    public static function load(){
    
        if(\Sequode\Application\Modules\Session\Modeler::isCookieValid() && \Sequode\Application\Modules\Session\Modeler::exists(\Sequode\Application\Modules\Session\Modeler::model()->session_id, 'session_id')){
            static::$modeler::exists(\Sequode\Application\Modules\Session\Modeler::get('user_id'),'id');
        }
    
    }
    
}