<?php

namespace Sequode\Application\Modules\Authed\Components;

use Sequode\Application\Modules\Traits\Components\CardsCardTrait;
use Sequode\Application\Modules\Traits\Components\CardsMenuCardTrait;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Application\Modules\Authed\Module;

class Cards {

    use CardsCardTrait,
        CardsMenuCardTrait;

    const Module = Module::class;

    const Icon = 'settings';

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