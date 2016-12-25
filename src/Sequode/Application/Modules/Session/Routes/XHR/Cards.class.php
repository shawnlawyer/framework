<?php

namespace Sequode\Application\Modules\Session\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

use Sequode\Application\Modules\Session\Module;
    
class Cards {
    
    public static $module = Module::class;
    
    public static function details($_model_id=0, $dom_id = 'CardsContainer'){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        if(!(
            $modeler::exists($_model_id,'id')
        )){ return; }
        return CardKitJS::placeCard(ModuleCard::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    
    public static function search($dom_id = 'CardsContainer'){
        $module = static::$module;
        return CardKitJS::placeCard(ModuleCard::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    
}