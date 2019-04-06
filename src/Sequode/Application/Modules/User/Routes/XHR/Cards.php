<?php

namespace Sequode\Application\Modules\User\Routes\XHR;

use Sequode\Application\Modules\User\Module;
use Sequode\Application\Modules\Traits\Routes\XHR\CardsCardRouteTrait as XHRCardsCardRouteTrait;

class Cards {

    use XHRCardsCardRouteTrait;

    public static $module = Module::class;
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