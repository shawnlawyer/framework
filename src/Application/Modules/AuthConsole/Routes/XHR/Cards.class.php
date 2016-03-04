<?php

namespace Sequode\Application\Modules\AuthConsole\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Card as ModuleCard;

use Sequode\Component\Card\Kit\JS as CardKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

use Sequode\Application\Modules\AuthConsole\Module;

use Sequode\Application\Modules\Console\Traits\Routes\XHR\CardsConsoleMenusTrait;

class Cards {
    
    use CardsConsoleMenusTrait;
    
    public static $module = Module::class;
    
    public static function index($dom_id = 'CardsContainer'){
        
        $js = array();
        $deck = array();
        $deck[] = ModuleCard::render($module::$registry_key, 'feature', true, true, true);
        $js[] = CardKitJS::placeDeck($deck, $dom_id);
        $deck = array();
        $deck[] = ModuleCard::render(\Sequode\Application\Modules\Auth\Module::$registry_key, 'login');
        $deck[] = ModuleCard::render(\Sequode\Application\Modules\Register\Module::$registry_key, 'signup');
        $js[] = CardKitJS::placeDeck($deck, $dom_id, false);
        
        return implode('', $js);
        
    }
}