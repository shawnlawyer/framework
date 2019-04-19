<?php

namespace Sequode\Application\Modules\Token\Routes\XHR;

use Sequode\Application\Modules\Token\Module;
use Sequode\Application\Modules\Traits\Routes\XHR\CardsCardRouteTrait as XHRCardsCardRouteTrait;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

class Cards {

    use XHRCardsCardRouteTrait;
    
    const Module = Module::class;

    public static $routes = [
        'details',
        'search',
        'tokens',
        'favorites'
    ];

    const Routes = [
        'details',
        'search',
        'tokens',
        'favorites'
    ];

}