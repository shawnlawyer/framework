<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Application\Modules\Session\Authority as SessionAuthority;
use Sequode\Application\Modules\Session\Modeler as SessionModeler;
use Sequode\Application\Modules\Session\Operations as SessionOperations;

trait ORMModelLoadSignedInTrait {
	
    public static function load(){
    
        $modeler = static::$modeler;
        
        if(SessionAuthority::isCookieValid() && SessionModeler::exists(SessionModeler::model()->session_id, 'session_id')){
            
            $modeler::exists(SessionOperations::get('user_id'),'id');
            
        }
    
    }
    
}