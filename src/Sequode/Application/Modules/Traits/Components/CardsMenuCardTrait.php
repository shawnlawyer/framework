<?php

namespace Sequode\Application\Modules\Traits\Components;

use Sequode\Component\Card\Kit as CardKit;

trait CardsMenuCardTrait {

    public static function menu($card = null){

        $_o = $card ?: static::card();

        $_o->menu->items =  static::menuItems();

        return $_o;

    }

}