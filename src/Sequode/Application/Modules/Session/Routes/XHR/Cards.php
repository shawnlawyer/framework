<?php

namespace Sequode\Application\Modules\Session\Routes\XHR;

use Sequode\Application\Modules\Session\Module;
use Sequode\Application\Modules\Traits\Routes\XHR\CardsCardRouteTrait as XHRCardsCardRouteTrait;

class Cards {

    use XHRCardsCardRouteTrait;

    const Module = Module::class;

    public static $routes = [
        'details',
        'search'
    ];

    public static function details($_model_id=0){

        extract((static::Module)::variables());
        
        if(!(
            $modeler::exists($_model_id,'id')
        )){ return false; }

    }

}