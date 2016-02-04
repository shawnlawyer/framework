<?php

namespace Sequode\Application\Modules\Authed;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Operations {
    public static $package = 'Authed';
	public static function logout(){
		\SQDE_Session::end();
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $modeler::model(null);
		\SQDE_Session::start();
		\SQDE_Session::set('console','Auth');
        return;
	}
}