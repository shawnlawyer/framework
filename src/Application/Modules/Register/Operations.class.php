<?php

namespace Sequode\Application\Modules\Register;

use Sequode\Application\Modules\Prototype\Operations\User\ORMModelSignupTrait;

class Operations {
    
    use ORMModelSignupTrait;
    
    public static $modeler = Modeler::class;
    
}