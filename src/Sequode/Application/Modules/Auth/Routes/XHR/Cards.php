<?php

namespace Sequode\Application\Modules\Auth\Routes\XHR;

use Sequode\Application\Modules\Auth\Module;
use Sequode\Component\Card\Traits\CardsTrait;

class Cards {

    use CardsTrait;

    public static $module = Module::class;
    public static $routes = [
        'login'
    ];
}