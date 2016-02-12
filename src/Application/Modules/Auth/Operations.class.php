<?php

namespace Sequode\Application\Modules\Auth;

use Sequode\Application\Modules\Prototype\Operations\User\ORMModelLoadSignedInTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSignOutTrait;

class Operations {
    
    use  ORMModelLoadSignedInTrait,
            ORMModelSignOutTrait;
    
    public static $modeler = Module::$modeler;
    
}