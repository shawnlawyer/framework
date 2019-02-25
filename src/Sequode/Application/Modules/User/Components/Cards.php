<?php

namespace Sequode\Application\Modules\User\Components;

use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\Card\CardKit as CardKit;

use Sequode\Application\Modules\User\Module;

use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Role\Modeler as RoleModeler;

class Cards {
    
    public static $module = Module::class;
    
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
        
        $_o = [];
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('New User','operations/user/newUser');
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('New Guest','operations/user/newGuest');
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Search Users','cards/user/search');
        
        return $_o;
        
    }
    
    public static function modelOperationsMenuItems($filter='', $_model = null){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
		
        $_o = [];
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Details', 'cards/user/details', [$modeler::model()->id]);
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Delete', 'operations/user/delete', [$modeler::model()->id]);
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Login As', 'operations/user/loginAs', [$modeler::model()->id]);
        
        return $_o;
    }
    public static function details($_model = null){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
		
        $_o = (object) null;
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'user-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        
        $_o->head = 'User Detail';
        $_o->body = [''];
        
        $_o->body[] = CardKitHTML::sublineBlock('Name');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/user/updateName', [$modeler::model()->id]), $modeler::model()->name, 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Password');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/user/updatePassword', [$modeler::model()->id]), 'Set Password', 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Role');
        RoleModeler::exists($modeler::model()->role_id,'id');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/user/updateRole', [$modeler::model()->id]), RoleModeler::model()->name, 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Active Status');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/user/updateActive', [$modeler::model()->id]), (($modeler::model()->active == 1) ? 'Active' : 'Suspended'), 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Sign Up Date');
        $_o->body[] = date('g:ia \o\n l jS F Y',$modeler::model()->sign_up_date);
        $_o->body[] = CardKitHTML::sublineBlock('Allowed Sequode Count');
        $_o->body[] = $modeler::model()->allowed_sequode_count;
        $_o->body[] = CardKitHTML::sublineBlock('Favorite Sequodes');
        $_o->body[] = $modeler::model()->sequode_favorites;
        $_o->body[] = CardKitHTML::sublineBlock('Email');
        $_o->body[] = $modeler::model()->email;
        $_o->body[] = CardKit::ownedItemsCollectionTile('Sequode', 'Sequodes Created : ', $modeler::model());
        $_o->body[] = CardKit::ownedItemsCollectionTile('Package', 'Packages Created : ', $modeler::model());
        $_o->body[] = CardKit::ownedItemsCollectionTile('Token', 'Tokens Created : ', $modeler::model());
        $_o->body[] = CardKit::nextInCollection((object) ['model_id'=>$modeler::model()->id,'details_route'=>'cards/user/details']);
        
        if(AccountAuthority::isSystemOwner()){
            $_o->body[] = CardKitHTML::modelId($modeler::model());
        }
        
        return $_o;
        
    }
    
    public static function search(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        ($_model == null)
            ? forward_static_call_array([$modeler,'model'], [])
            : forward_static_call_array([$modeler, 'model'], [$_model]);
		
        $_o = (object) null;
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'user-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = [];
        
        $search_components_array = ModuleForm::render($module::$registry_key, 'search');
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
        $_o->body[] = CardKit::collectionCard((object) ['collection'=>'user_search','icon'=>'user','card_route'=>'cards/user/search','details_route'=>'cards/user/details']);
        return $_o;
    }
    
}