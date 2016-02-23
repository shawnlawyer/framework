<?php

namespace Sequode\Application\Modules\Register;

use Sequode\Application\Modules\User\Traits\Operations\ORMModelSignupTrait;

class Operations {
    
    use ORMModelSignupTrait;
    
    public static $modeler = Modeler::class;
    
}