<?php

namespace Sequode\Application\Modules\User\Components;

use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Application\Modules\User\Module;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Role\Modeler as RoleModeler;

class Cards {
    
    const Module = Module::class;
    
    public static function menu(){
        
        $_o = (object) null;

        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'user-icon-background';
        $_o->menu = (object) null;
        $_o->menu->position_adjuster =  'automagic-card-menu-right-side-adjuster';
        $_o->menu->items =  self::menuItems();
        
        return $_o;
        
    }
    
    public static function menuItems(){

        extract((static::Module)::variables());

        $_o = [];

        $_o[] = CardKit::onTapEventsXHRCallMenuItem('New User', $module::xhrOperationRoute('newUser'));
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('New Guest', $module::xhrOperationRoute('newGuest'));
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Search Users', $module::xhrCardRoute('search'));
        
        return $_o;
        
    }
    
    public static function modelOperationsMenuItems($filter='', $_model = null){

        extract((static::Module)::variables());
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
		
        $_o = [];

        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Details', $module::xhrCardRoute('details'), [$modeler::model()->id]);
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Delete', $module::xhrOperationRoute('delete'), [$modeler::model()->id]);
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Login As', $module::xhrOperationRoute('loginAs'), [$modeler::model()->id]);
        
        return $_o;
    }

    public static function details($_model = null){

        extract((static::Module)::variables());
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
		
        $_o = (object) null;

        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'users',
            'node' => $modeler::model()->id
        ];
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'user-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        $_o->head = 'User Detail';
        $_o->body = [''];
        $_o->body[] = CardKitHTML::sublineBlock('Name');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject( $module::xhrFormRoute('updateName'), [$modeler::model()->id]), $modeler::model()->name, 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Password');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject($module::xhrFormRoute('updatePassword'), [$modeler::model()->id]), 'Set Password', 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Role');
        RoleModeler::exists($modeler::model()->role_id,'id');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject($module::xhrFormRoute('updateRole'), [$modeler::model()->id]), RoleModeler::model()->name, 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Active Status');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject($module::xhrFormRoute('updateActive'), [$modeler::model()->id]), (($modeler::model()->active == 1) ? 'Active' : 'Suspended'), 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Sign Up Date');
        $_o->body[] = date('g:ia \o\n l jS F Y',$modeler::model()->sign_up_date);
        $_o->body[] = CardKitHTML::sublineBlock('Allowed Sequode Count');
        $_o->body[] = $modeler::model()->allowed_sequode_count;
        $_o->body[] = CardKitHTML::sublineBlock('Favorite Sequodes');
        $_o->body[] = json_encode($modeler::model()->sequode_favorites);
        $_o->body[] = CardKitHTML::sublineBlock('Email');
        $_o->body[] = $modeler::model()->email;
        $_o->body[] = CardKit::ownedItemsCollectionTile('Sequode', $modeler::model(), 'Sequodes');
        $_o->body[] = CardKit::ownedItemsCollectionTile('Package', $modeler::model(), 'Packages');
        $_o->body[] = CardKit::ownedItemsCollectionTile('Token', $modeler::model(), 'Tokens ');
        $_o->body[] = CardKit::nextInCollection((object) ['model_id' => $modeler::model()->id, 'details_route' => $module::xhrCardRoute('details')]);
        
        return $_o;
        
    }
    
    public static function search(){

        extract((static::Module)::variables());
		
        $_o = (object) null;

        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'user_search',
            'teardown' => 'function(){cards = undefined;}'
        ];
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'user-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = [];
        
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
        $_o->body[] = CardKit::collectionCard((object) ['collection'=>'user_search','icon'=>'user', 'card_route' => $module::xhrCardRoute('search'), 'details_route' => $module::xhrCardRoute('details')]);

        return $_o;

    }
    
}