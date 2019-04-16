<?php

namespace Sequode\Application\Modules\Package\Routes\XHR;

use Sequode\Application\Modules\Package\Module;
use Sequode\Application\Modules\Traits\Routes\XHR\CardsCardRouteTrait as XHRCardsCardRouteTrait;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

class Cards {

    use XHRCardsCardRouteTrait;

    const Module = Module::class;

    public static $merge = true;

    public static $routes = [
        'details',
        'search',
        'packages'
    ];

    const Routes = [
        'details',
        'search',
        'packages'
    ];

}