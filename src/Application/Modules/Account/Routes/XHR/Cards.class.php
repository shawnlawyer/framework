<?php

namespace Sequode\Application\Modules\Account\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

class Cards {
    public static $module_registry_key = 'Account';
	public static $merge = false;
	public static $routes = array(
		'details',
		'updatePassword',
		'updateEmail'
	);
	public static $routes_to_methods = array(
		'details' => 'details',
		'updatePassword' => 'updatePassword',
		'updateEmail' => 'updateEmail'
    );
    public static function updatePassword($dom_id = 'CardsContainer'){
        $dialog = ModuleRegistry::model(static::$module_registry_key)->xhr->dialogs[__FUNCTION__];
        if(!SessionStore::is($dialog['session_store_key'])){
            SessionStore::set($dialog['session_store_key'], $dialog['session_store_setup']);
        }
        return CardKitJS::placeCard(ModuleCard::render(static::$module_registry_key,__FUNCTION__), $dom_id);
    }
    public static function updateEmail($dom_id = 'CardsContainer'){
        $dialog = ModuleRegistry::model(static::$module_registry_key)->xhr->dialogs[__FUNCTION__];
        if(!SessionStore::is($dialog['session_store_key'])){
            SessionStore::set($dialog['session_store_key'], $dialog['session_store_setup']);
        }
        return CardKitJS::placeCard(ModuleCard::render(static::$module_registry_key,__FUNCTION__), $dom_id);
    }
    public static function details($dom_id = 'CardsContainer'){
        return CardKitJS::placeCard(ModuleCard::render(static::$module_registry_key,__FUNCTION__), $dom_id);
    }
}