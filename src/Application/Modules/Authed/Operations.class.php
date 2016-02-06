<?php

namespace Sequode\Application\Modules\Authed;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Operations {
    public static $package = 'Authed';
	public static function logout(){
		\Sequode\Application\Modules\Session\Modeler::end();
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $modeler::model(null);
		\Sequode\Application\Modules\Session\Modeler::start();
		\Sequode\Application\Modules\Session\Modeler::set('console','Auth');
        return;
	}
}