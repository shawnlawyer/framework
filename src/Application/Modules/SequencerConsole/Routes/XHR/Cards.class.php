<?php

namespace Sequode\Application\Modules\Console\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Card as ModuleCard;

use Sequode\Component\Card\Kit\JS as CardKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

use Sequode\Application\Modules\Console\Module;

use Sequode\Application\Modules\Console\Traits\Routes\XHR\CardsConsoleMenusTrait;

class Cards {
    
    use CardsConsoleMenusTrait;
    
    public static $module = Module::class;
    
    public static function index($dom_id = 'CardsContainer'){
        
        $module = static::$module;
        return CardKitJS::placeCard(ModuleCard::render($module::$registry_key, __FUNCTION__, array(\Sequode\Application\Modules\Account\Modeler::model())), $dom_id);
    }
    
}