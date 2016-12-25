<?php

namespace Sequode\Application\Modules\Register;

use Sequode\Application\Modules\User\Traits\Operations\ORMModelSignupTrait;

use Sequode\Application\Modules\User\Modeler;

class Operations {
    
    use ORMModelSignupTrait;
    
    public static $modeler = Modeler::class;
    
}