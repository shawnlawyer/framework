<?php

namespace Sequode\Application\Modules\Traits\Components;

use Sequode\Application\Modules\Account\Module as AccountModule;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\FormInput as FormInputComponent;
use Sequode\View\Module\Form as ModuleForm;

trait CardsFavoritesCardTrait {

    public static function favorites(){

        extract((static::Module)::variables());

        $_o = static::card();

        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => $collections::Method_To_Collection[__FUNCTION__],
            'teardown' => 'function(){cards = undefined;}'
        ];
        $_o->size = 'fullscreen';
        $_o->menu->items = self::menuItems();

        $_o->head = 'Favorites';

        $dom_id = FormInputComponent::uniqueHash('','');
        $_o->menu->items[] = [
            'css_classes'=>'automagic-card-menu-item noSelect',
            'id'=>$dom_id,
            'contents'=>'Empty Favorites',
            'js_action'=> DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject(AccountModule::xhrOperationRoute('emptyFavorites'),[DOMElementKitJS::jsQuotedValue( $module::Registry_Key )]))
        ];

        $_o->body[] = CardKit::collectionCard((object) ['collection'=>$collections::Method_To_Collection[__FUNCTION__],'icon'=>'user','card_route' => $module::xhrCardRoute('favorites'),'details_route' => $module::xhrCardRoute('details')]);

        return $_o;

    }

}