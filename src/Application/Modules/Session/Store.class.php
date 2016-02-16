<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Foundation\Hashes;
use Sequode\Model\Application\Configuration;

use Sequode\Application\Modules\Session\Traits\Operation\ORMModelCreate;
use Sequode\Application\Modules\Session\Traits\Operation\ManageSessionStore;

class Store {
    
    use  ManageSessionStore;
    
    public static $modeler = Modeler::class;
    public static $operations = Operations::class;
    
    
    
}