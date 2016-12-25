<?php

namespace Sequode\Application\Modules\Token;

use Sequode\Application\Modules\Token\Traits\Operations\ORMModelCreateTrait;
use Sequode\Application\Modules\Token\Traits\Operations\ORMModelUpdateNameTrait;
use Sequode\Application\Modules\Token\Traits\Operations\ORMModelDeleteTrait;;

use Sequode\Application\Modules\Token\Traits\Operations\ORMModelGetOwnedModelsTrait;

class Operations {
    
    use ORMModelCreateTrait,
        ORMModelGetOwnedModelsTrait,
        ORMModelUpdateNameTrait,
        ORMModelDeleteTrait;
    
    public static $modeler = Modeler::class;
    
}