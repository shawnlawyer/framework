<?php

namespace Sequode\Application\Modules\Traits\Components;

use Sequode\Component\Card\Kit as CardKit;

trait CardsCollectionCardTrait {

    public static function collectionCard($method, $head = null){

        extract((static::Module)::variables());

        $_o = static::card();

        $_o->context = (object)[
            'card' => $module::xhrCardRoute($method),
            'collection' => $collections::Method_To_Collection[$method],
            'teardown' => 'function(){cards = undefined;}'
        ];
        $_o->head = $head ?: ucwords(str_replace('_', ' ', $method));
        $_o->menu->items = self::menuItems([$module::xhrCardRoute($method)]);
        $_o->body[] = CardKit::collectionCard((object) ['collection' => $collections::Method_To_Collection[$method], 'icon' => $_o->icon, 'card_route' => $module::xhrCardRoute($method), 'details_route' => $module::xhrCardRoute('details')]);

        return $_o;

    }

}