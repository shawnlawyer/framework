<?php

namespace Sequode\Application\Modules\Token;

use Sequode\Application\Modules\Token\Traits\Operations\ORMModelCreateTrait;
use Sequode\Application\Modules\Token\Traits\Operations\ORMModelUpdateNameTrait;
use Sequode\Application\Modules\Token\Traits\Operations\ORMModelDeleteTrait;

class Operations {
    
    use  ORMModelCreateTrait,
            ORMModelUpdateNameTrait,
            ORMModelDeleteTrait;
    
    public static $modeler = Modeler::class;
    
}