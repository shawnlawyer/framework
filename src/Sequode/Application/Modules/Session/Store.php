<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Foundation\Traits\StaticStoreTrait;
use Sequode\Application\Modules\Session\Traits\Operations\IsSetGetClearSessionStore;
use Sequode\Application\Modules\Traits\Modeler\ActiveModelTrait;

class Store {
    
    use StaticStoreTrait,
        IsSetGetClearSessionStore,
        ActiveModelTrait;
        
    public static $operations = Operations::class;
}