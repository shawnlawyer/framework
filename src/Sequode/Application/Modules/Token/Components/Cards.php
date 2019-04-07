<?php

namespace Sequode\Application\Modules\Token\Components;

use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\FormInput as FormInputComponent;

use Sequode\View\Module\Form as ModuleForm;
use Sequode\Application\Modules\Token\Module;

use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;
    
class Cards {

    const Module = Module::class;

    public static $tiles = ['myTile'];
    
    public static function menu(){

        $_o = (object) null;
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->position_adjuster =  'automagic-card-menu-right-side-adjuster';
        $_o->menu->items =  array_merge(self::menuItems(), self::collectionOwnedMenuItems());

        return $_o;

    }

    public static function menuItems(){

        extract((static::Module)::variables());

        $_o = [];

        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Search Tokens', $module::xhrCardRoute('search'));
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('New Token',  $module::xhrOperationRoute('newToken'));
        
        return $_o;
        
    }
    
    public static function collectionOwnedMenuItems($user_model = null, $fields='id,name'){

        extract((static::Module)::variables());

        if($user_model == null ){

            $user_model = AccountModeler::model();

        }

        $models = $operations::getOwnedModels($user_model, $fields, 20)->all;

        $items = [];

        if(count($models) > 0){

            $items[] = CardKit::onTapEventsXHRCallMenuItem('My Tokens', $module::xhrCardRoute('my'));

            foreach($models as $model){

                $items[] = CardKit::onTapEventsXHRCallMenuItem($model->name, $module::xhrCardRoute('details'), [$model->id]);

            }

        }

        return $items;
        
    }
    
    public static function modelOperationsMenuItems($filter='', $_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
		
        $_o = [];

        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Details', $module::xhrCardRoute('details'), [$modeler::model()->id]);
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Delete', $module::xhrOperationRoute('delete'), [$modeler::model()->id]);

        return $_o;
    }
    
    public static function details($_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
        
        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'tokens',
            'node' => $modeler::model()->id
        ];
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        $_o->head = 'Token Details';
        $_o->body = [''];
        $_o->body[] = CardKitHTML::sublineBlock('Name');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject($module::xhrFormRoute('name'), [$modeler::model()->id]), $modeler::model()->name, 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Token');
        $_o->body[] = $modeler::model()->token;
        $_o->body[] = CardKit::nextInCollection((object) ['model_id' => $modeler::model()->id, 'details_route' => $module::xhrCardRoute('details')]);

        if(AccountAuthority::isSystemOwner()){

            $_o->body[] = CardKitHTML::modelId($modeler::model());

        }

        return $_o;
    }
    
    public static function my(){

        extract((static::Module)::variables());

        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'tokens'
        ];
        $_o->size = 'fullscreen';
        $_o->head = 'My Tokens';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items =  [];
        
        $dom_id = FormInputComponent::uniqueHash('','');
        $_o->menu->items[] = [
            'css_classes'=>'automagic-card-menu-item noSelect',
            'id'=>$dom_id,
            'contents'=>'New Token',
            'js_action'=> DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject($module::xhrOperationRoute('newToken')))
        ];
        $_o->body = [];
        $_o->body[] = CardKit::collectionCard((object) ['collection' => 'tokens', 'icon' => 'atom', 'card_route' => $module::xhrCardRoute('my'), 'details_route' => $module::xhrCardRoute('details')]);

        return $_o;

    }

    public static function search(){

        extract((static::Module)::variables());

        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'token_search',
            'teardown' => 'function(){cards = undefined;}'
        ];
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
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
        $_o->body[] = CardKit::collectionCard((object) ['collection' => 'token_search', 'icon' => 'atom', 'card_route' => $module::xhrCardRoute('details'), 'details_route' => $module::xhrCardRoute('details')]);

        return $_o;

    }
    
    public static function myTile($user_model=null){

        extract((static::Module)::variables());

        if($user_model == null ){

            $user_model = AccountModeler::model();

        }
        
        $_o = (object) null;
        $_o->head = 'Tokens';
        $_o->size = 'xsmall';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items =  [];
        $_o->menu->item[] = CardKit::onTapEventsXHRCallMenuItem('New Token', $module::xhrOperationRoute('newToken'));
        $_o->body = [];
        $_o->body[] = '';
        $_o->body[] = CardKit::ownedItemsCollectionTile('Token', $user_model, 'Tokens Created : ');
        
        return $_o;
        
    }
    
}