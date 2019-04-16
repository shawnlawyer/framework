<?php

namespace Sequode\Application\Modules\Traits\Routes\XHR;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Card as CardComponent;
use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Component\Card\Kit\JS as CardKitJS;
use Sequode\View\Module\Card as ModuleCard;
use Sequode\Application\Modules\Traits\Routes\XHR\OperationsDialogTrait as XHROperationsDialogTrait;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;

trait CardsCardRouteTrait {

    public static function card($method, $parameters = []){

        extract((static::Module)::variables());

        if(in_array(XHROperationsDialogTrait::class, class_uses($component_cards, true)) && defined($component_cards . '::Dialogs') && in_array($method, $component_cards::Dialogs)) {

            $dialog = forward_static_call_array([$component_dialogs, $method], []);

            if (!SessionStore::is($dialog['session_store_key'])) {

                SessionStore::set($dialog['session_store_key'], $dialog['session_store_setup']);

            }

        }

        $dom_id = (array_key_exists('dom_id', $parameters) && !empty($parameters['dom_id']))
            ? $parameters['dom_id']
            : 'CardsContainer';

        unset($parameters['dom_id']);

        $card_object = forward_static_call_array([$component_cards, $method],is_array($parameters) ? $parameters : []);

        return CardKitJS::placeCard(CardComponent::render($card_object), $dom_id);

    }

    public static function prepInputValues($route, $args){

        if(method_exists(static::class, $route)){

            return (forward_static_call_array([static::class, $route], $args)) ?: [];

        }elseif(!empty($args)){

            return ["dom_id" => end($args)];

        }else{

            return [];

        }

    }

    public static function details($_model_id=0, $dom_id=null){

        extract((static::Module)::variables());

        if(!(
            $modeler::exists($_model_id,'id')
            && (AccountAuthority::isOwner( $modeler::model() )
                || AccountAuthority::isSystemOwner())
        )){return false;}

        return [
            "model" => $modeler::model(),
            "dom_id" => $dom_id
        ];

    }


}