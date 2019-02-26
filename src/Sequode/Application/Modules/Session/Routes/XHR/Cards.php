<?php

namespace Sequode\Application\Modules\Session\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;
use Sequode\Application\Modules\Traits\Routes\XHR\CardsCardRouteTrait as XHRCardsCardRouteTrait;

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