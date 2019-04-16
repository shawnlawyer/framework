<?php

namespace Sequode\Application\Modules\Authed\Components;

use Sequode\Component\Card\Kit as CardKit;
use Sequode\Application\Modules\Authed\Module;

class Cards {
    
    const Module = Module::class;

    public static function menu(){

        $_o = (object) null;

        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'settings-icon-background';
        $_o->menu = (object) null;
        $_o->menu->position_adjuster =  'automagic-card-menu-right-side-adjuster';
        $_o->menu->items = self::menuItems();

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