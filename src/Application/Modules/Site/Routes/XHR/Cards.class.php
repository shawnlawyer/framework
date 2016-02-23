<?php

namespace Sequode\Application\Modules\Site\Routes\XHR;

use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

use Sequode\Application\Modules\Site\Module;
    
class Cards {
    
    public static $module = Module::class;
    
    public static function sequode($dom_id = 'CardsContainer'){
        
        $js = array();
        $deck = array();
        $deck[] = ModuleCard::render(\Sequode\Application\Modules\Site\Module::$registry_key, 'sequode', true, true, true);
        $js[] = CardKitJS::placeDeck($deck, $dom_id);
        $deck = array();
        $deck[] = ModuleCard::render(\Sequode\Application\Modules\Auth\Module::$registry_key, 'login');
        $deck[] = ModuleCard::render(\Sequode\Application\Modules\Registry\Module::$registry_key, 'signup');
        $js[] = CardKitJS::placeDeck($deck, $dom_id, false);
        
        return implode('', $js);
        
    }
    
}