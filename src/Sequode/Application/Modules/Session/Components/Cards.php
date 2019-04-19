<?php

namespace Sequode\Application\Modules\Session\Components;

use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Account\Module as AccountModule;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\FormInput as FormInputComponent;
use Sequode\View\Export\PHPClosure;
use Sequode\Application\Modules\Session\Module;
    
class Cards {
    
    const Module = Module::class;

    const Tiles = ['search', 'favorites'];
    
    public static function menu(){
        
        $_o = (object) null;

        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'session-icon-background';
        $_o->menu = (object) null;
        $_o->menu->position_adjuster =  'automagic-card-menu-right-side-adjuster';
        $_o->menu->items =  self::menuItems();
        
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
        
        $_o = (object) null;

        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'sessions',
            'node' => $modeler::model()->id
        ];
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'session-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items =  static::menuItems() + static::modelMenuItems();
        
        $items[] = CardKit::onTapEventsXHRCallMenuItem('Delete Session', $module::xhrOperationRoute('destroy'), [$modeler::model()->id]);

        $_o->head = 'Session Detail';
        $_o->body = [''];

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

    public static function search($_model = null){

        extract((static::Module)::variables());
        
        $_o = (object) null;

        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'session_search',
            'teardown' => 'function(){cards = undefined;}'
        ];
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'session-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = [];
        
        $search_components_array = ModuleForm::render($module::Registry_Key,'search');
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
        $_o->body[] = CardKit::collectionCard((object) ['collection'=>'session_search', 'icon'=>'atom', 'card_route'=>$module::xhrCardRoute('search'), 'details_route'=>$module::xhrCardRoute('details')]);
        
        return $_o;
        
    }

    public static function favorites(){

        extract((static::Module)::variables());

        $_o = (object) null;

        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'session_favorites',
            'teardown' => 'function(){cards = undefined;}'
        ];
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'session-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = self::menuItems();

        $_o->head = 'Session Favorites';

        $dom_id = FormInputComponent::uniqueHash('','');
        $_o->menu->items[] = [
            'css_classes'=>'automagic-card-menu-item noSelect',
            'id'=>$dom_id,
            'contents'=>'Empty Favorites',
            'js_action'=> DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject(AccountModule::xhrOperationRoute('emptyFavorites'),[DOMElementKitJS::jsQuotedValue( $module::Registry_Key )]))
        ];

        $_o->body = [];
        $_o->body[] = CardKit::collectionCard((object) ['collection'=>'session_favorites','icon'=>'session','card_route' => $module::xhrCardRoute('favorites'),'details_route' => $module::xhrCardRoute('details')]);

        return $_o;

    }

}