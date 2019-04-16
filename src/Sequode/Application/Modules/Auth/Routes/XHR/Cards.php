<?php

namespace Sequode\Application\Modules\Auth\Routes\XHR;

use Sequode\Application\Modules\Auth\Module;
use Sequode\Application\Modules\Traits\Routes\XHR\CardsCardRouteTrait as XHRCardsCardRouteTrait;

class Cards {

    use XHRCardsCardRouteTrait;

    const Module = Module::class;

    public static $routes = [
        'login'
    ];

    const Routes = [
        'login'
    ];

}