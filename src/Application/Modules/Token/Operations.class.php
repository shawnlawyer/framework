<?php

namespace Sequode\Application\Modules\Token;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Application\Modules\Prototype\Operations\ORMModelUpdateNameTrait;
use Sequode\Application\Modules\Prototype\Operations\ORMModelDeleteTrait;

class Operations {
    
    use  ORMModelUpdateNameTrait,
            ORMModelDeleteTrait;
    
    public static $modeler = Modeler::class;
    
    
}