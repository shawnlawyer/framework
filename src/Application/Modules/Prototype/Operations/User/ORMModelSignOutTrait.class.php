<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelSignOutTrait {
    
	public static function logout(){
                
        $modeler = static::$modeler;
        
		\Sequode\Application\Modules\Session\Modeler::end();
        $modeler::model(null);
		\Sequode\Application\Modules\Session\Modeler::start();
		\Sequode\Application\Modules\Session\Modeler::set('console','Auth');
        return;
	}
    
}