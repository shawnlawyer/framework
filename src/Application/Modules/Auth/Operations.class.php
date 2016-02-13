<?php

namespace Sequode\Application\Modules\Auth;

use Sequode\Application\Modules\Account\Modeler;

use Sequode\Application\Modules\Prototype\Operations\User\ORMModelLoadSignedInTrait;
use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSignInTrait;


class Operations {
    
    use  ORMModelLoadSignedInTrait,
            ORMModelSignInTrait;
    
    public static $modeler = Modeler::class;
    
}