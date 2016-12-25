<?php

namespace Sequode\Application\Modules\Account\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

use Sequode\Application\Modules\Account\Module;

class Cards {
    
    public static $module = Module::class;
    
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
        $module = static::$module;
        $dialog = $module::model()->xhr->dialogs[__FUNCTION__];
        if(!SessionStore::is($dialog['session_store_key'])){
            SessionStore::set($dialog['session_store_key'], $dialog['session_store_setup']);
        }
        return CardKitJS::placeCard(ModuleCard::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    public static function updateEmail($dom_id = 'CardsContainer'){
        $module = static::$module;
        $dialog = $module::model()->xhr->dialogs[__FUNCTION__];
        if(!SessionStore::is($dialog['session_store_key'])){
            SessionStore::set($dialog['session_store_key'], $dialog['session_store_setup']);
        }
        return CardKitJS::placeCard(ModuleCard::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
    public static function details($dom_id = 'CardsContainer'){
        $module = static::$module;
        return CardKitJS::placeCard(ModuleCard::render($module::$registry_key, __FUNCTION__), $dom_id);
    }
}