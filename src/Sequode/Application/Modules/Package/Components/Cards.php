<?php

namespace Sequode\Application\Modules\Package\Components;

use Sequode\Application\Modules\Account\Module as AccountModule;
use Sequode\Application\Modules\Package\Module;
use Sequode\Application\Modules\Traits\Components\CardsCollectionCardTrait;
use Sequode\Application\Modules\Traits\Components\CardsFavoritesCardTrait;
use Sequode\Application\Modules\Traits\Components\CardsMenuCardTrait;
use Sequode\Application\Modules\Traits\Components\CardsSearchCardTrait;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\FormInput as FormInputComponent;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;

class Cards {

    use CardsMenuCardTrait,
        CardsSearchCardTrait,
        CardsFavoritesCardTrait,
        CardsCollectionCardTrait;

    const Module = Module::class;

    const Tiles = [
        'packages',
        'favorites',
        'search'
    ];

    public static function card(){

        $_o = (object) null;
        $_o->head = 'Package Tools';
        $_o->icon = 'atom';
        $_o->menu = (object) null;
        $_o->menu->items = [];
        $_o->menu->position = '';
        $_o->size = 'fullscreen';
        $_o->body = [];

        return $_o;

    }

    public static function menu(){
        
        $_o = (object) null;

        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->position_adjuster =  'automagic-card-menu-right-side-adjuster';
        $_o->menu->items =  self::menuItems();
        
        return $_o;
        
    }

    public static function menuItems($filters=[]){

        extract((static::Module)::variables());

        $_o = [];

        $_o[$module::xhrCardRoute('packages')] = CardKit::onTapEventsXHRCallMenuItem('Packages', $module::xhrCardRoute('packages'));
        $_o[$module::xhrCardRoute('favorites')] = CardKit::onTapEventsXHRCallMenuItem('Favorite Packages', $module::xhrCardRoute('favorites'));
        $_o[$module::xhrCardRoute('search')] = CardKit::onTapEventsXHRCallMenuItem('Search Packages', $module::xhrCardRoute('search'));
        $_o[$module::xhrOperationRoute('newPackage')] = CardKit::onTapEventsXHRCallMenuItem('New Package', $module::xhrOperationRoute('newPackage'));

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
            'collection' => 'packages',
            'node' => $_model->id
        ];
        $_o->size = 'large';
        $_o->menu->items = self::modelMenuItems([$module::xhrCardRoute(__FUNCTION__)]);
        $_o->head = 'Package Details';
        $_o->body[] = '';
        $_o->body[] = (object) ['js' => 'registry.setContext({card:\''. $module::xhrCardRoute('details').'\',collection:\'packages\',node:\''.$modeler::model()->id.'\'});'];
        $_o->body[] = CardKitHTML::sublineBlock('Name');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject($module::xhrFormRoute('name'), [$modeler::model()->id]), $modeler::model()->name, 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Package Sequode');
        $_o->body[] = ($modeler::model()->sequode_id != 0 && SequodeModeler::exists($modeler::model()->sequode_id,'id')) ? DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject( $module::xhrFormRoute('packageSequode'), [$_model->id]), SequodeModeler::model()->name, 'settings') : ModuleForm::render($module::Registry_Key,'packageSequode')[0];
        $_o->body[] = CardKitHTML::sublineBlock('Package Token');
        $_o->body[] = $modeler::model()->token;
        $_o->body[] = CardKitHTML::sublineBlock('<a target="_blank" href="/source/'.$modeler::model()->token.'">Download</a>');
        $_o->body[] = CardKit::nextInCollection((object) ['model_id' => $modeler::model()->id, 'details_route' => $module::xhrCardRoute('details')]);

        return $_o;
        
    }

    public static function packages(){

        return static::collectionCard('owned', 'Packages');

    }
    
}