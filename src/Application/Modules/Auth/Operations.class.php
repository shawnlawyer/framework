<?php

namespace Sequode\Application\Modules\Auth;

use Sequode\Application\Modules\Account\Modeler;

use Sequode\Application\Modules\Prototype\Operations\User\ORMModelLoadSignedInTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSignOutTrait;


class Operations {
    
    use  ORMModelLoadSignedInTrait,
            ORMModelSignOutTrait;
    
    public static $modeler = Modeler::class;
    
}