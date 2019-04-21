<?php

namespace Sequode\Application\Modules\Traits\Components;

use Sequode\Component\Card\Kit as CardKit;

trait CardsMenuCardTrait {

    public static function menu(){

        $_o = static::card();

        $_o->menu->items =  self::menuItems();

        return $_o;

    }

}