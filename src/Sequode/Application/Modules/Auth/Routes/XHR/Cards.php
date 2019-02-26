<?php

namespace Sequode\Application\Modules\Auth\Routes\XHR;

use Sequode\Application\Modules\Traits\Routes\XHR\CardsCardRouteTrait as XHRCardsCardRouteTrait;

class Cards {

    use XHRCardsCardRouteTrait;

    public static $module = Module::class;

    public static $routes = [
        'login'
    ];
}