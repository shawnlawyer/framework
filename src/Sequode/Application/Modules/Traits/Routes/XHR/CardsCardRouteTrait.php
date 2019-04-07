<?php

namespace Sequode\Application\Modules\Traits\Routes\XHR;


use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Card as CardComponent;
use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Component\Card\Kit\JS as CardKitJS;
use Sequode\View\Module\Card as ModuleCard;
use Sequode\Application\Modules\Traits\Routes\XHR\OperationsDialogTrait as XHROperationsDialogTrait;

trait CardsCardRouteTrait {

    public static function card($method, $parameters = []){

        extract((static::Module)::variables());

        if(in_array(XHROperationsDialogTrait::class, class_uses($component_cards, true)) && isset($component_cards::$dialogs) && in_array($method, $component_cards::$dialogs)) {

            $dialog = forward_static_call_array([$component_dialogs, $method], []);

            if (!SessionStore::is($dialog['session_store_key'])) {

                SessionStore::set($dialog['session_store_key'], $dialog['session_store_setup']);

            }

        }

        $dom_id = 'CardsContainer';

        $card_object = forward_static_call_array([$component_cards, $method],is_array($parameters) ? $parameters : []);

        return CardKitJS::placeCard(CardComponent::render($card_object), $dom_id);

    }

}