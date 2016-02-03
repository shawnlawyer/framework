<?php

namespace Sequode\Application\Modules\Auth\Routes\XHR;

use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

class SQDE_AuthCardsXHR {
    public static $package = 'Auth';
    public static function login($dom_id = 'CardsContainer'){
        return CardKitJS::placeCard(ModuleCard::render(self::$package,__FUNCTION__), $dom_id);
    }
}