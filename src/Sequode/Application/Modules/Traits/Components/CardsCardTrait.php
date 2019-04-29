<?php

namespace Sequode\Application\Modules\Traits\Components;

use Sequode\Component\Card\Kit as CardKit;

trait CardsCardTrait {


    public static function card(){

        extract((static::Module)::variables());

        $_o = (object) null;
        $_o->head = $module::Registry_Key. ' Tools';
        $_o->icon = (defined(static::class. '::Icon')) ? static::Icon : 'sequode';
        $_o->menu = (object) null;
        $_o->menu->items = [];
        $_o->menu->position = '';
        $_o->size = 'fullscreen';
        $_o->body = [];

        return $_o;

    }

}