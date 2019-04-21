<?php

namespace Sequode\Application\Modules\User\Components;

use Sequode\Application\Modules\Account\Module as AccountModule;
use Sequode\Application\Modules\Traits\Components\CardsMenuCardTrait;
use Sequode\Component\FormInput as FormInputComponent;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Application\Modules\User\Module;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Role\Modeler as RoleModeler;
use Sequode\Application\Modules\Traits\Components\CardsSearchCardTrait;
use Sequode\Application\Modules\Traits\Components\CardsFavoritesCardTrait;
use Sequode\Application\Modules\Traits\Components\CardsCollectionCardTrait;

class Cards {

    use CardsMenuCardTrait,
        CardsSearchCardTrait,
        CardsFavoritesCardTrait,
        CardsCollectionCardTrait;
    
    const Module = Module::class;

    const Tiles = [
        'admins',
        'users',
        'guests',
        'search',
        'favorites'
    ];

    public static function card(){

        $_o = (object) null;
        $_o->head = 'User Tools';
        $_o->icon = 'user';
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

        $_o[$module::xhrCardRoute('admins')] = CardKit::onTapEventsXHRCallMenuItem('Admins', $module::xhrCardRoute('admins'));
        $_o[$module::xhrCardRoute('users')] = CardKit::onTapEventsXHRCallMenuItem('Users', $module::xhrCardRoute('users'));
        $_o[$module::xhrOperationRoute('newUser')] = CardKit::onTapEventsXHRCallMenuItem('New User', $module::xhrOperationRoute('newUser'));
        $_o[$module::xhrCardRoute('guests')] = CardKit::onTapEventsXHRCallMenuItem('Guests', $module::xhrCardRoute('guests'));
        $_o[$module::xhrOperationRoute('newGuest')] = CardKit::onTapEventsXHRCallMenuItem('New Guest', $module::xhrOperationRoute('newGuest'));
        $_o[$module::xhrCardRoute('search')] = CardKit::onTapEventsXHRCallMenuItem('Search Users', $module::xhrCardRoute('search'));
        $_o[$module::xhrCardRoute('favorites')] = CardKit::onTapEventsXHRCallMenuItem('Favorite Users', $module::xhrCardRoute('favorites'));

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
        $_o[$module::xhrOperationRoute('loginAs')] = CardKit::onTapEventsXHRCallMenuItem('Login As', $module::xhrOperationRoute('loginAs'), [$modeler::model()->id]);

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
            'collection' => 'users',
            'node' => $modeler::model()->id
        ];
        $_o->size = 'large';
        $_o->menu->items = self::modelMenuItems([$module::xhrCardRoute(__FUNCTION__)]);
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
        $_o->body[] = CardKitHTML::sublineBlock('Favorites');
        $_o->body[] = ($modeler::model()->favorites);
        $_o->body[] = CardKitHTML::sublineBlock('Email');
        $_o->body[] = $modeler::model()->email;
        $_o->body[] = CardKit::nextInCollection((object) ['model_id' => $modeler::model()->id, 'details_route' => $module::xhrCardRoute('details')]);
        
        return $_o;
        
    }

    public static function users(){

        return static::collectionCard(__FUNCTION__);

    }

    public static function guests(){

        return static::collectionCard(__FUNCTION__);

    }

    public static function admins(){

        return static::collectionCard(__FUNCTION__);

    }

}