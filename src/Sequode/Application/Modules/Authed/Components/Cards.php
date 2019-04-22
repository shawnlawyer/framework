<?php

namespace Sequode\Application\Modules\Authed\Components;

use Sequode\Application\Modules\Traits\Components\CardsMenuCardTrait;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Application\Modules\Authed\Module;

class Cards {

    use CardsMenuCardTrait;

    const Module = Module::class;

    public static function card(){

        $_o = (object) null;
        $_o->head = 'Authed Tools';
        $_o->icon = 'settings';
        $_o->menu = (object) null;
        $_o->menu->items = [];
        $_o->menu->position = '';
        $_o->size = 'fullscreen';
        $_o->body = [];

        return $_o;

    }

    public static function menuItems($filters=[]){

        extract((static::Module)::variables());

        $_o = [];

        $_o[$module::xhrOperationRoute('logout')] = CardKit::onTapEventsXHRCallMenuItem('Logout', $module::xhrOperationRoute('logout'));

        foreach($filters as $filter){

            unset($_o[$filter]);

        }

        return $_o;

    }

}