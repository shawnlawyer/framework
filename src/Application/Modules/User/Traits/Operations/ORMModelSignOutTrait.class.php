<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Application\Modules\Session\Operations as SessionOperations;

trait ORMModelSignOutTrait {
    
	public static function logout(){
                
        $modeler = static::$modeler;
        
		SessionOperations::end();
        $modeler::model(null);
		SessionOperations::start();
		SessionOperations::set('console','Auth');
        
        return;
        
	}
    
}