<?php

namespace Sequode\Application\Modules\Authed\Components;

use Sequode\Component\Card\CardKit as CardKit;

class Cards {
    public static $registry_key = 'Authed';
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
        return array(
            CardKit::onTapEventsXHRCallMenuItem('Logout','operations/authed/logout')
        );
    }
}