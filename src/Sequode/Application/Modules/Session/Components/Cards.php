<?php

namespace Sequode\Application\Modules\Session\Components;

use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Account\Module as AccountModule;
use Sequode\Application\Modules\Traits\Components\CardsCollectionCardTrait;
use Sequode\Application\Modules\Traits\Components\CardsFavoritesCardTrait;
use Sequode\Application\Modules\Traits\Components\CardsMenuCardTrait;
use Sequode\Application\Modules\Traits\Components\CardsSearchCardTrait;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\FormInput as FormInputComponent;
use Sequode\View\Export\PHPClosure;
use Sequode\Application\Modules\Session\Module;
    
class Cards {

    use CardsMenuCardTrait,
        CardsSearchCardTrait,
        CardsFavoritesCardTrait,
        CardsCollectionCardTrait;

    const Module = Module::class;

    const Tiles = [
        'search',
        'favorites'
    ];

    public static function card(){

        $_o = (object) null;
        $_o->head = 'Session Tools';
        $_o->icon = 'session';
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

        $_o[$module::xhrCardRoute('search')] = CardKit::onTapEventsXHRCallMenuItem('Search Sessions', $module::xhrCardRoute('search'));

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
        $_o[$module::xhrOperationRoute('destroy')] = CardKit::onTapEventsXHRCallMenuItem('Delete Session', $module::xhrOperationRoute('destroy'), [$modeler::model()->id]);

        foreach($filters as $filter){

            unset($_o[$filter]);

        }

        return $_o;
    }

    public static function details($_model=null){

        extract((static::Module)::variables());
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
        
        $_o = static::card();

        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'sessions',
            'node' => $modeler::model()->id
        ];

        $_o->size = 'large';
        $_o->menu->items = static::modelMenuItems([$module::xhrCardRoute(__FUNCTION__)]);
        
        $items[] = CardKit::onTapEventsXHRCallMenuItem('Delete Session', $module::xhrOperationRoute('destroy'), [$modeler::model()->id]);

        $_o->head = 'Session Detail';
        $_o->body[] = '';

        if($modeler::model()->session_id === $operations::getCookieValue()) {
            $_o->body[] = CardKitHTML::sublineBlock('This your current session');
        }

        $_o->body[] = CardKitHTML::sublineBlock('Session Id');
        $_o->body[] = $modeler::model()->session_id;
        $_o->body[] = CardKitHTML::sublineBlock('Name');
        $_o->body[] = $modeler::model()->name;
        $_o->body[] = CardKitHTML::sublineBlock('Ip Address');
        $_o->body[] = $modeler::model()->ip_address;
        $_o->body[] = CardKitHTML::sublineBlock('Data');
        $_o->body[] = '<textarea style="width:100%; height:10em;">'.PHPClosure::export($modeler::model()->session_data).'</textarea>';
        $_o->body[] = CardKitHTML::sublineBlock('Session Started');
        $_o->body[] = date('g:ia \o\n l jS F Y',    $modeler::model()->session_start);

        return $_o;
        
    }

}