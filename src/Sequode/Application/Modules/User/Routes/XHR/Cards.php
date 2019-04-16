<?php

namespace Sequode\Application\Modules\User\Routes\XHR;

use Sequode\Application\Modules\User\Module;
use Sequode\Application\Modules\Traits\Routes\XHR\CardsCardRouteTrait as XHRCardsCardRouteTrait;

class Cards {

    use XHRCardsCardRouteTrait;

    const Module = Module::class;

    public static $routes = [
        'details',
        'search',
        'users',
        'guests',
        'admins'
    ];

    const Routes = [
        'details',
        'search',
        'users',
        'guests',
        'admins'
    ];

}