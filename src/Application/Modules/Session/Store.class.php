<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Foundation\Traits\StaticStoreTrait;
use Sequode\Application\Modules\Session\Traits\Operations\IsSetGetClearSessionStore;

class Store extends \Sequode\Foundation\Modeler {
    
    use StaticStoreTrait,
        IsSetGetClearSessionStore;
        
    public static $operations = Operations::class;
}