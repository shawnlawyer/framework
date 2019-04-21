<?php

namespace Sequode\Application\Modules\Traits\Components;

use Sequode\Component\Card\Kit as CardKit;
use Sequode\View\Module\Form as ModuleForm;

trait CardsSearchCardTrait {

    public static function search(){

        extract((static::Module)::variables());

        $_o = static::card();

        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => $collections::Method_To_Collection[__FUNCTION__],
            'teardown' => 'function(){cards = undefined;}'
        ];

        $_o->size = 'fullscreen';

        $search_components_array = ModuleForm::render($module::Registry_Key, 'search');

        $_o->head = $search_components_array[0];

        array_shift($search_components_array);

        foreach($search_components_array as $key => $object){

            $_o->menu->items[] = [
                'css_classes'=>'automagic-card-menu-item noSelect',
                'contents'=>$object->html,
                'js_action'=> $object->js
            ];

        }

        $_o->body = [];
        $_o->body[] = CardKit::collectionCard((object)[
            'collection' => $collections::Method_To_Collection[__FUNCTION__],
            'icon'=>$_o->icon,
            'card_route' => $module::xhrCardRoute('search'),
            'details_route' => $module::xhrCardRoute('details')
        ]);

        return $_o;

    }

}