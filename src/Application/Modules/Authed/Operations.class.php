<?php

namespace Sequode\Application\Modules\Authed;

use Sequode\Application\Modules\User\Traits\Operations\ORMModelSignOutTrait;

use Sequode\Application\Modules\Account\Modeler;

class Operations {
    
    use ORMModelSignOutTrait;
    
    public static $modeler = Modeler::class;
    
}