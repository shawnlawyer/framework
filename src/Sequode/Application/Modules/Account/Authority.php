<?php

namespace Sequode\Application\Modules\Account;

use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\User\Authority as UserAuthority;

class Authority extends UserAuthority {
    
    public static $modeler = Modeler::class;

    public static function isAuthenticated(){
        $modeler = static::$modeler;
        return (AccountModeler::model()->id) ? true : false;
    }
}