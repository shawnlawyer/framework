<?php

namespace Sequode\Application\Modules\User\Components;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\Card\CardKit as CardKit;

class Cards {
    public static $package = 'User';
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
        $_o = array();
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('New User','operations/user/newUser');
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('New Guest','operations/user/newGuest');
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Search Users','cards/user/search');
        return $_o;
    }
    public static function modelOperationsMenuItems($filter='', $_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $_model = ($_model == null ) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'), array($_model));
        $_o = array();
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Details', 'cards/user/details', array($_model->id));
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Delete', 'operations/user/delete', array($_model->id));
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Login As', 'operations/user/loginAs', array($_model->id));
        return $_o;
    }
    public static function details($_model = null){
        $modeler = ModuleRegistry::model(static::$package)->modeler;
        $_model = ($_model == null ) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'), array($_model));
        
        $_o = (object) null;
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'user-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        
        $_o->head = 'User Detail';
        $_o->body = array('');
        
        $_o->body[] = CardKitHTML::sublineBlock('Username');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/user/updateName', array($_model->id)), $_model->username, 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Password');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/user/updatePassword', array($_model->id)), 'Set Password', 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Role');
        \Sequode\Application\Modules\Role\Modeler::exists($_model->role_id,'id');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/user/updateRole', array($_model->id)), \Sequode\Application\Modules\Role\Modeler::model()->name, 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Active Status');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/user/updateActive', array($_model->id)), (($_model->active == 1) ? 'Active' : 'Suspended'), 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Sign Up Date');
        $_o->body[] = date('g:ia \o\n l jS F Y',$_model->sign_up_date);
        $_o->body[] = CardKitHTML::sublineBlock('Allowed Sequode Count');
        $_o->body[] = $_model->allowed_sequode_count;
        $_o->body[] = CardKitHTML::sublineBlock('Favorite Sequodes');
        $_o->body[] = $_model->sequode_favorites;
        $_o->body[] = CardKitHTML::sublineBlock('Email');
        $_o->body[] = $_model->email;
        $_o->body[] = CardKit::collectionTile('Sequode', 'Sequodes Created : ', $_model);
        $_o->body[] = CardKit::collectionTile('Package', 'Packages Created : ', $_model);
        $_o->body[] = CardKit::collectionTile('Token', 'Tokens Created : ', $_model);
        $_o->body[] = CardKit::nextInCollection((object) array('model_id'=>$_model->id,'details_route'=>'cards/user/details'));
        if(\Sequode\Application\Modules\Account\Authority::isSystemOwner()){
            $_o->body[] = CardKitHTML::modelId($_model);
        }
        return $_o;
    }
    public static function search(){
        $_o = (object) null;
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'user-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = array();
        
        $search_components_array = ModuleForm::render(self::$package,'search');
        $_o->head = $search_components_array[0];
        array_shift($search_components_array);
        
        foreach($search_components_array as $key => $object){
            $_o->menu->items[] = array(
                'css_classes'=>'automagic-card-menu-item noSelect',
                'contents'=>$object->html,
                'js_action'=> $object->js                
            );
        }
        $_o->body = array();
        $_o->body[] = CardKit::collectionCard((object) array('collection'=>'user_search','icon'=>'user','card_route'=>'cards/user/search','details_route'=>'cards/user/details'));
        return $_o;
    }
}