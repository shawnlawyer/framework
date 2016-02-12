<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

trait ORMModelSignOutTrait {
    
	public static function logout(){
		\Sequode\Application\Modules\Session\Modeler::end();
        static::$modeler::model(null);
		\Sequode\Application\Modules\Session\Modeler::start();
		\Sequode\Application\Modules\Session\Modeler::set('console','Auth');
        return;
	}
    
}