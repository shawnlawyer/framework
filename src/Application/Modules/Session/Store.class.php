<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Application\Models\ORM\Sessions as Model;
use Sequode\Foundation\StaticStoreTrait;
use Sequode\Application\Modules\Session\Traits\Operations\IsSetGetClearSessionStore;

class Store extends \Sequode\Patterns\Modeler {
    
    use StaticStoreTrait,
        IsSetGetClearSessionStore;
        
    public static $operations = Operations::class;
}