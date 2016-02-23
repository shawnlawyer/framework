<?php

namespace Sequode\Application\Modules\Package\Routes\XHR;

use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

use Sequode\Application\Modules\Package\Module;

class Cards {
    
    public static $module = Module::class;
    
    public static function details($_model_id=0, $dom_id = 'CardsContainer'){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        if(!(
        $modeler::exists($_model_id,'id')
        && (\Sequode\Application\Modules\Account\Authority::isOwner( $modeler::model() )
        || \Sequode\Application\Modules\Account\Authority::isSystemOwner())
        )){return;}
        return CardKitJS::placeCard(ModuleCard::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    public static function search($dom_id = 'CardsContainer'){
        $module = static::$module;
        return CardKitJS::placeCard(ModuleCard::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    public static function my($dom_id = 'CardsContainer'){
        $module = static::$module;
        return CardKitJS::placeCard(ModuleCard::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
}