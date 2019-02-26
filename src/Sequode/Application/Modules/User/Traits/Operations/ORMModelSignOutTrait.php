<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Application\Modules\Session\Operations as SessionOperations;
use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Application\Modules\Authed\Module as AuthedModule;
use Sequode\Application\Modules\User\Modeler as UserModeler;
use Sequode\Application\Modules\Account\Module as AccountModule;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;

trait ORMModelSignOutTrait {
    
	public static function logout(){
                
        $modeler = static::$modeler;
        $modeler::model(null);
        UserModeler::model(null);
        AccountModeler::model(null);
        SessionOperations::end();
        SessionOperations::start();
        SessionOperations::load();
        ModuleRegistry::load();
        
        return;
        
	}
    
}