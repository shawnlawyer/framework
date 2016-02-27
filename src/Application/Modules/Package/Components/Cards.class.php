<?php

namespace Sequode\Application\Modules\Package\Components;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Form as ModuleForm;

use Sequode\Component\Card\CardKit as CardKit;
use Sequode\Component\Card\Kit\JS as CardKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\FormInput\FormInput as FormInputComponent;

use Sequode\Application\Modules\Package\Module;

class Cards {
    
    public static $module = Module::class;
    public static $tiles = array('myTile');
    
    public static function menu(){
        $_o = (object) null;
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->position_adjuster =  'automagic-card-menu-right-side-adjuster';
        $_o->menu->items =  self::menuItems();
        return $_o;
    }
    public static function menuItems(){
        $_o = array();
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('New Package','operations/package/newPackage');
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('My Packages','cards/package/my');
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Search Packages','cards/package/search');
        return $_o;
    }
    public static function modelOperationsMenuItems($filter='', $_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = ($_model == null ) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'), array($_model));
        $_o = array();
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Details','cards/package/details',array($_model->id));
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Delete','cards/package/delete',array($_model->id));
        return $_o;
    }
    public static function details($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = ($_model == null ) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'), array($_model));
        
        $_o = (object) null;
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        
        $_o->head = 'Package Details';
        $_o->body = array('');
        $_o->body[] = (object) array('js' => 'registry.setContext({card:\'cards/package/details\',collection:\'packages\',node:\''.$_model->id.'\'});');
        $_o->body[] = CardKitHTML::sublineBlock('Name');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/package/name', array($_model->id)), $_model->name, 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Package Sequode');
        $_o->body[] = ($_model->sequode_id != 0 && \Sequode\Application\Modules\Sequode\Modeler::exists($_model->sequode_id,'id')) ? DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/package/packageSequode', array($_model->id)), \Sequode\Application\Modules\Sequode\Modeler::model()->name, 'settings') : ModuleForm::render(static::$registry_key,'packageSequode')[0];
        $_o->body[] = CardKitHTML::sublineBlock('Package Token');
        $_o->body[] = $_model->token;
        $_o->body[] = CardKitHTML::sublineBlock('<a target="_blank" href="/source/'.$_model->token.'">Download</a>');
        
        $_o->body[] = CardKit::nextInCollection((object) array('model_id'=>$_model->id,'details_route'=>'cards/package/details'));
        if(\Sequode\Application\Modules\Account\Authority::isSystemOwner()){
            $_o->body[] = CardKitHTML::modelId($_model);
        }
        return $_o;
    }
    public static function my(){
        $_o = (object) null;
        $_o->size = 'fullscreen';
        $_o->head = 'My Packages';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items =  array();
        
        $dom_id = FormInputComponent::uniqueHash('','');
        $_o->menu->items[] = array(
            'css_classes'=>'automagic-card-menu-item noSelect',
            'id'=>$dom_id,
            'contents'=>'New Package',
            'js_action'=> DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject('operations/package/newPackage'))
        );
        $_o->body = array();
        $_o->body[] = CardKit::collectionCard((object) array('collection'=>'packages','icon'=>'atom','card_route'=>'cards/package/my','details_route'=>'cards/package/details'));
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
        
        $search_components_array = ModuleForm::render($module::$registry_key,'search');
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
        $_o->body[] = CardKit::collectionCard((object) array('collection'=>'package_search','icon'=>'atom','card_route'=>'cards/package/search','details_route'=>'cards/package/details'));
        return $_o;
    }
    
    public static function myTile($user_model=null){
        
        $module = static::$module;
        
        if($user_model == null ){ $user_model = \Sequode\Application\Modules\Account\Modeler::model(); }
        $_o = (object) null;
        $_o->head = 'Sequence Sets';
        $_o->size = 'xsmall';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items =  array();
        $_o->menu->item[] = CardKit::onTapEventsXHRCallMenuItem('New Set','operations/token/newPackage');
        $_o->body = array();
        $_o->body[] = '';
        $_o->body[] = CardKit::collectionTile('Package', 'Sets Created : ', $user_model);
        return $_o;
    }
}