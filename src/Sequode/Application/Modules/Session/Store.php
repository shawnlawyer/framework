<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Foundation\Traits\StaticStoreTrait;
use Sequode\Application\Modules\Session\Traits\Operations\IsSetGetClearSessionStore;
use Sequode\Model\Traits\ModelerTrait;

class Store {
    
    use StaticStoreTrait,
        IsSetGetClearSessionStore,
        ModelerTrait;
        
    public static $operations = Operations::class;
}