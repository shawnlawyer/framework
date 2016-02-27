<?php

namespace Sequode\Application\Modules\Token\Components;

use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\CardKit as CardKit;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\FormInput\FormInput as FormInputComponent;

use Sequode\View\Module\Form as ModuleForm;
use Sequode\Application\Modules\Token\Module;
    
class Cards {
    
    public static $module = Module::class;
    public static $tiles = array('myTile');
    
    public static function menu(){
        $_o = (object) null;
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->position_adjuster =  'automagic-card-menu-right-side-adjuster';
        $_o->menu->items =  array_merge(self::menuItems(),self::collectionOwnedMenuItems());
        return $_o;
    }
    public static function menuItems(){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $context = $module::model($package)->context;
        
        $_out = array();
        $_out[] = CardKit::onTapEventsXHRCallMenuItem('Search Tokens', 'cards/token/search');
        $_out[] = CardKit::onTapEventsXHRCallMenuItem('New Token', 'operations/token/newToken');
        
        return $_out;
        
    }
    
    public static function collectionOwnedMenuItems($user_model = null, $fields='id,name'){
        
        if($user_model == null ){
            $user_model = \Sequode\Application\Modules\Account\Modeler::model();
        }
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $operations = $module::model($package)->operations;
        $context = $module::model($package)->context;
        $models = $operations::getOwnedModels($user_model, $fields, 20)->all;
        $items = array();
        if(count($models) > 0){
            $items[] = CardKit::onTapEventsXHRCallMenuItem('My Tokens', 'cards/'.$context.'/my');
            foreach($models as $model){
                $items[] = CardKit::onTapEventsXHRCallMenuItem($model->name, 'cards/'.$context.'/details', array($model->id));
            }
        }
        return $items;
        
    }
    
    public static function modelOperationsMenuItems($filter='', $_model = null){
    
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        ($_model == null)
            ? forward_static_call_array(array($modeler,'model'), array())
            : forward_static_call_array(array($modeler, 'model'), array($_model));
		
        $_o = array();
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Details', 'cards/token/details', array($modeler::model()->id));
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Delete', 'cards/token/delete', array($modeler::model()->id));
        return $items;
    }
    
    public static function details($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $_model = ($_model == null ) ? forward_static_call_array(array($modeler, 'model'), array()) : forward_static_call_array(array($modeler, 'model'), array($_model));
        
        $_o = (object) null;
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        
        $_o->head = 'Token Details';
        $_o->body = array('');
        $_o->body[] = (object) array('js' => 'registry.setContext({card:\'cards/token/details\',collection:\'tokens\',node:\''.$_model->id.'\'});');
        $_o->body[] = CardKitHTML::sublineBlock('Name');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/token/name', array($_model->id)), $_model->name, 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Token');
        $_o->body[] = $_model->token;
        
        
        $_o->body[] = CardKit::nextInCollection((object) array('model_id'=>$_model->id,'details_route'=>'cards/token/details'));
        if(\Sequode\Application\Modules\Account\Authority::isSystemOwner()){
            $_o->body[] = CardKitHTML::modelId($_model);
        }
        return $_o;
    }
    
    public static function my(){
        $_o = (object) null;
        $_o->size = 'fullscreen';
        $_o->head = 'My Tokens';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items =  array();
        
        $dom_id = FormInputComponent::uniqueHash('','');
        $_o->menu->items[] = array(
            'css_classes'=>'automagic-card-menu-item noSelect',
            'id'=>$dom_id,
            'contents'=>'New Token',
            'js_action'=> DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject('operations/token/newToken'))
        );
        $_o->body = array();
        $_o->body[] = CardKit::collectionCard((object) array('collection'=>'tokens','icon'=>'atom','card_route'=>'cards/token/my','details_route'=>'cards/token/details'));
        return $_o;
    }
    public static function search(){
        
        $module = static::$module;
        
        $_o = (object) null;
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = array();
        
        $search_components_array = ModuleForm::render($module::$registry_key, 'search');
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
        $_o->body[] = CardKit::collectionCard((object) array('collection'=>'token_search','icon'=>'atom','card_route'=>'cards/token/my','details_route'=>'cards/token/details'));
        return $_o;
    }
    
    public static function myTile($user_model=null){
        
        if($user_model == null ){
            $user_model = \Sequode\Application\Modules\Account\Modeler::model();
        }
        
        $_o = (object) null;
        $_o->head = 'Tokens';
        $_o->size = 'xsmall';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items =  array();
        $_o->menu->item[] = CardKit::onTapEventsXHRCallMenuItem('New Token','operations/token/newToken');
        $_o->body = array();
        $_o->body[] = '';
        $_o->body[] = CardKit::collectionTile('Token', 'Tokens Created : ', $user_model);
        
        return $_o;
        
    }
    
}