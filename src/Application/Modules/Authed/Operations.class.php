<?php

namespace Sequode\Application\Modules\Authed;

use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSignOutTrait;

class Operations {
    
    use ORMModelSignOutTrait;
    
    public static $modeler = Module::$modeler;
}