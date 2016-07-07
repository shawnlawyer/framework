<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Application\Modules\Session\Operations as SessionOperations;
use Sequode\Application\Modules\Session\Store as SessionStore;

trait ORMModelSignOutTrait {
    
	public static function logout(){
                
        $modeler = static::$modeler;
        
		SessionOperations::end();
        $modeler::model(null);
		SessionOperations::start();
        
        return;
        
	}
    
}