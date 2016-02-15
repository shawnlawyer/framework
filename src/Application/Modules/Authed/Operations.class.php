<?php

namespace Sequode\Application\Modules\Authed;

use Sequode\Application\Modules\User\Traits\Operations\ORMModelSignOutTrait;

class Operations {
    
    use ORMModelSignOutTrait;
    
    public static $modeler = Module::$modeler;
}