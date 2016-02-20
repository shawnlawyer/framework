<?php

namespace Sequode\Application\Modules\User\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

class Cards {
    public static $registry_key = 'User';
    public static function details($_model_id=0, $dom_id = 'CardsContainer'){
        $modeler = ModuleRegistry::model(static::$registry_key)->modeler;
        if(!(
            $modeler::exists($_model_id,'id')
        )){ return; }
        return CardKitJS::placeCard(ModuleCard::render(static::$registry_key,__FUNCTION__), $dom_id);
    }
    public static function search($dom_id = 'CardsContainer'){
        return CardKitJS::placeCard(ModuleCard::render(static::$registry_key,__FUNCTION__), $dom_id);
    }
}