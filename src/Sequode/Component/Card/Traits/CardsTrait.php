<?php

namespace Sequode\Component\Card\Traits;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Component\Card\Kit\JS as CardKitJS;
use Sequode\View\Module\Card as ModuleCard;

trait CardsTrait {

    public static function card($method, $parameters = []){
        $module = static::$module;
        $cards = $module::model()->components->cards;
        if(in_array('Sequode\\Component\\Dialog\\Traits\\OperationsTrait', class_uses($cards, true)) && isset($cards::$dialogs) && in_array($method, $cards::$dialogs)) {
            $dialog = $module::model()->xhr->dialogs[__FUNCTION__];
            if (!SessionStore::is($dialog['session_store_key'])) {
                SessionStore::set($dialog['session_store_key'], $dialog['session_store_setup']);
            }
        }
        $dom_id = 'CardsContainer';
        return CardKitJS::placeCard(ModuleCard::render($module::$registry_key, $method, $parameters), $dom_id);
    }
}