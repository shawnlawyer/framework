<?php

namespace Sequode\Application\Modules\Token;

use Sequode\Application\Modules\Prototype\Operations\Token\ORMModelCreateTrait;
use Sequode\Application\Modules\Prototype\Operations\Token\ORMModelUpdateNameTrait;
use Sequode\Application\Modules\Prototype\Operations\Token\ORMModelDeleteTrait;

class Operations {
    
    use  ORMModelCreateTrait,
            ORMModelUpdateNameTrait,
            ORMModelDeleteTrait;
    
    public static $modeler = Modeler::class;
    
}