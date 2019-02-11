<?php

namespace Sequode\Application\Modules\Auth;

use Sequode\Application\Modules\Account\Modeler;

use Sequode\Application\Modules\User\Traits\Operations\ORMModelLoadSignedInTrait;
use Sequode\Application\Modules\User\Traits\Operations\ORMModelSignInTrait;


class Operations {

    use  ORMModelLoadSignedInTrait,
            ORMModelSignInTrait;

    public static $modeler = Modeler::class;
    
}