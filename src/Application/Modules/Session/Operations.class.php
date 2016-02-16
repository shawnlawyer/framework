<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Foundation\Hashes;
use Sequode\Model\Application\Configuration;

use Sequode\Application\Modules\Session\Traits\Operation\ORMModelCreate;
use Sequode\Application\Modules\Session\Traits\Operation\ManageSessionStore;

class Operations {
    
    use  ORMModelCreate,
            ManageSessionStore,
            ManageSessionCookie;
    
    public static $modeler = Modeler::class;
    
}