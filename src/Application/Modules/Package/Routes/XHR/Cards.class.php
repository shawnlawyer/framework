<?php

namespace Sequode\Application\Modules\Package\Routes\XHR;

use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

class Cards {
    public static $registry_key = 'Package';
    public static $modeler = \Sequode\Application\Modules\Package\Modeler::class;
    public static function details($_model_id=0, $dom_id = 'CardsContainer'){
        $modeler = static::$modeler;
        if(!(
        $modeler::exists($_model_id,'id')
        && (\Sequode\Application\Modules\Account\Authority::isOwner( $modeler::model() )
        || \Sequode\Application\Modules\Account\Authority::isSystemOwner())
        )){return;}
        return CardKitJS::placeCard(ModuleCard::render(static::$registry_key,__FUNCTION__), $dom_id);
    }
    public static function search($dom_id = 'CardsContainer'){
        return CardKitJS::placeCard(ModuleCard::render(static::$registry_key,__FUNCTION__), $dom_id);
    }
    public static function my($dom_id = 'CardsContainer'){
        return CardKitJS::placeCard(ModuleCard::render(static::$registry_key,__FUNCTION__), $dom_id);
    }
}