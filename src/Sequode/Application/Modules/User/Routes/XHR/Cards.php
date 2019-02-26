<?php

namespace Sequode\Application\Modules\User\Routes\XHR;

use Sequode\Application\Modules\User\Module;
use Sequode\Application\Modules\Traits\Routes\XHR\CardsCardRouteTrait as XHRCardsCardRouteTrait;
use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

class Cards {

    use XHRCardsCardRouteTrait;

    public static $module = Module::class;

    public static $routes = [
        'details',
        'search'
    ];
    
    public static function details($_model_id=0){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(!(
        $modeler::exists($_model_id,'id')
        )){ return false; }
    }
}