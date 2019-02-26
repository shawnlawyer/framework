<?php

namespace Sequode\Application\Modules\Account;

use Sequode\Application\Modules\User\Authority as UserAuthority;

class Authority extends UserAuthority {
    
    public static $modeler = Modeler::class;
    
}