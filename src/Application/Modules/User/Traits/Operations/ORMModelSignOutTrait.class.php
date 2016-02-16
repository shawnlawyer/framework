<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

trait ORMModelSignOutTrait {
    
	public static function logout(){
                
        $modeler = static::$modeler;
        
		\Sequode\Application\Modules\Session\Operations::end();
        $modeler::model(null);
		\Sequode\Application\Modules\Session\Operations::start();
		\Sequode\Application\Modules\Session\Operations::set('console','Auth');
        
        return;
        
	}
    
}