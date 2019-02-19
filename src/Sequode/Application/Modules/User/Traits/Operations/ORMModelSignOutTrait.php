<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Application\Modules\Session\Operations as SessionOperations;
use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\Model\Application\Runtime as ApplicationRuntime;
use Sequode\Model\Module\Registry as ModuleRegistry;

use Sequode\Application\Modules\Authed\Module as AuthedModule;
use Sequode\Application\Modules\User\Modeler as UserModeler;
use Sequode\Application\Modules\Account\Module as AccountModule;

trait ORMModelSignOutTrait {
    
	public static function logout(){
                
        $modeler = static::$modeler;

        SessionOperations::end();
        $modeler::model(null);
        UserModeler::model(null);
		SessionOperations::start();
        SessionOperations::load();
        ModuleRegistry::load();

        
        return;
        
	}
    
}