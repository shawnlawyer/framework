<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Foundation\Hashes;
use Sequode\Model\Application\Configuration;

use Sequode\Application\Modules\Session\Traits\Operation\ORMModelCreate;
use Sequode\Application\Modules\Session\Traits\Operation\ManageSessionStore;
use Sequode\Application\Modules\Session\Traits\Operation\SetGetSessionCookie;

class Operations {
    
    use  ORMModelCreate,
            ManageSessionStore,
            SetGetSessionCookie;
    
    public static $modeler = Modeler::class;
    
}