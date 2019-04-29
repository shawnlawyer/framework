<?php

namespace Sequode\Application\Modules\Token\Components;

use Sequode\Application\Modules\Account\Module as AccountModule;
use Sequode\Application\Modules\Traits\Components\CardsCardTrait;
use Sequode\Application\Modules\Traits\Components\CardsCollectionCardTrait;
use Sequode\Application\Modules\Traits\Components\CardsFavoritesCardTrait;
use Sequode\Application\Modules\Traits\Components\CardsMenuCardTrait;
use Sequode\Application\Modules\Traits\Components\CardsSearchCardTrait;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\FormInput as FormInputComponent;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Application\Modules\Token\Module;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;
    
class Cards {

    use CardsCardTrait,
        CardsMenuCardTrait,
        CardsSearchCardTrait,
        CardsFavoritesCardTrait,
        CardsCollectionCardTrait;

    const Module = Module::class;

    const Icon = 'atom';

    const Tiles = [
        'tokens',
        'favorites',
        'search'
    ];

    public static function menuItems($filters=[]){

        extract((static::Module)::variables());

        $_o = [];

        $_o[$module::xhrCardRoute('search')] = CardKit::onTapEventsXHRCallMenuItem('Search Tokens', $module::xhrCardRoute('search'));
        $_o[$module::xhrCardRoute('favorites')] = CardKit::onTapEventsXHRCallMenuItem('Favorite Tokens', $module::xhrCardRoute('favorites'));
        $_o[$module::xhrCardRoute('tokens')] = CardKit::onTapEventsXHRCallMenuItem('Tokens', $module::xhrCardRoute('tokens'));
        $_o[$module::xhrOperationRoute('newToken')] = CardKit::onTapEventsXHRCallMenuItem('New Token',  $module::xhrOperationRoute('newToken'));

        foreach($filters as $filter){

            unset($_o[$filter]);

        }

        return $_o;
        
    }

    public static function modelMenuItems($filters=[], $_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
		
        $_o = [];

        if(AccountAuthority::isFavorited($module::Registry_Key, $modeler::model())){
            $_o[AccountModule::xhrOperationRoute('unfavorite')] = CardKit::onTapEventsXHRCallMenuItem('Remove From Favorited', AccountModule::xhrOperationRoute('unfavorite'), [DOMElementKitJS::jsQuotedValue( $module::Registry_Key ), $modeler::model()->id]);
        }else{
            $_o[AccountModule::xhrOperationRoute('favorite')] = CardKit::onTapEventsXHRCallMenuItem('Add To Favorites', AccountModule::xhrOperationRoute('favorite'), [DOMElementKitJS::jsQuotedValue( $module::Registry_Key ), $modeler::model()->id]);
        }

        $_o[$module::xhrCardRoute('details')] = CardKit::onTapEventsXHRCallMenuItem('Details', $module::xhrCardRoute('details'), [$modeler::model()->id]);
        $_o[$module::xhrOperationRoute('delete')] = CardKit::onTapEventsXHRCallMenuItem('Delete', $module::xhrOperationRoute('delete'), [$modeler::model()->id]);

        foreach($filters as $filter){

            unset($_o[$filter]);

        }

        return $_o;

    }
    
    public static function details($_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = static::card();

        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'tokens',
            'node' => $modeler::model()->id
        ];
        $_o->size = 'large';
        $_o->menu->items = self::modelMenuItems([$module::xhrCardRoute(__FUNCTION__)]);
        $_o->head = 'Token Details';
        $_o->body = [''];
        $_o->body[] = CardKitHTML::sublineBlock('Name');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject($module::xhrFormRoute('name'), [$modeler::model()->id]), $modeler::model()->name, 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Token');
        $_o->body[] = $modeler::model()->token;
        $_o->body[] = CardKit::nextInCollection((object) ['model_id' => $modeler::model()->id, 'details_route' => $module::xhrCardRoute('details')]);

        return $_o;

    }

    public static function tokens(){

        return static::collectionCard('owned','Tokens');

    }
    
}