<?php

namespace Sequode\Application\Modules\BlockedIP;

use Sequode\Application\Modules\BlockedIP\Traits\Operations\ORMModelCreateTrait;
use Sequode\Application\Modules\BlockedIP\Traits\Operations\ORMModelDeleteTrait;

class Operations {
    
    use  ORMModelCreateTrait,
            ORMModelDeleteTrait;

    public static $modeler = Modeler::class;
    
}