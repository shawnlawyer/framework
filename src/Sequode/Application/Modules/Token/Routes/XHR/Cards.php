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
        'my'
    ];
    
    public static function details($_model_id=0){

        extract((static::Module)::variables());

        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
            || AccountAuthority::isSystemOwner())
        )){return false;}

    }

}