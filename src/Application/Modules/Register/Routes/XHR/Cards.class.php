<?php

namespace Sequode\Application\Modules\Register\Routes\XHR;

use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

class Cards {
    public static $module_registry_key = Sequode\Application\Modules\Register\Module::class;
	public static $merge = false;
	public static $routes = array(
		'signup'
	);
	public static $routes_to_methods = array(
		'signup' => 'signup'
    );
    public static function signup($dom_id = 'CardsContainer'){
        return CardKitJS::placeCard(ModuleCard::render(static::$module_registry_key,__FUNCTION__), $dom_id);
    }
}