<?php

namespace Sequode\Application\Modules\Authed\Components;

use Sequode\Component\Card\Kit as CardKit;

use Sequode\Application\Modules\Authed\Module;

class Cards {
    
    public static $module = Module::class;
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

    public static function menuItems(){

        extract((static::Module)::variables());

        return [
            CardKit::onTapEventsXHRCallMenuItem('Logout', $module::xhrOperationRoute('logout'))
        ];

    }

}