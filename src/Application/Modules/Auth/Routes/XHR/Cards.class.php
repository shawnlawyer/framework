<?php

namespace Sequode\Application\Modules\Auth\Routes\XHR;

use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

use Sequode\Application\Modules\Auth\Module;

class Cards {
    
    public static $module = Module::class;
    
    public static function login($dom_id = 'CardsContainer'){
        
        $module = static::$module;
        return CardKitJS::placeCard(ModuleCard::render($module::$registry_key, __FUNCTION__), $dom_id);

    }
}