<?php

namespace Sequode\Application\Modules\Package\Components;

use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\CardKit as CardKit;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\FormInput\FormInput as FormInputComponent;
use Sequode\Application\Modules\Package\Module;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;

class Cards {
    
    public static $module = Module::class;
    public static $registry_key = 'Package';
    public static $tiles = ['myTile'];

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
        
        $_o = [];
        
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Search Packages','cards/package/search');
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('New Package','operations/package/newPackage');
        
        return $_o;
        
    }
    public static function collectionOwnedMenuItems($user_model = null, $fields='id,name'){
        
        if($user_model == null ){
            $user_model = AccountModeler::model();
        }
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $operations = $module::model($package)->operations;
        $context = $module::model($package)->context;
        $models = $operations::getOwnedModels($user_model, $fields, 10)->all;
        $items = [];
        if(count($models) > 0){
            $items[] = CardKit::onTapEventsXHRCallMenuItem('My Packages', 'cards/'.$context.'/my');
            foreach($models as $model){
                $items[] = CardKit::onTapEventsXHRCallMenuItem($model->name, 'cards/'.$context.'/details', [$model->id]);
            }
        }
        return $items;
        
    }
    public static function modelOperationsMenuItems($filter='', $_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = ($_model == null ) ? forward_static_call_array([$modeler,'model'], []) : forward_static_call_array([$modeler,'model'], [$_model]);
        $_o = [];
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Details','cards/package/details', [$_model->id]);
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Delete','operations/package/delete', [$_model->id]);
        
        return $_o;
    }
    public static function details($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = ($_model == null ) ? forward_static_call_array([$modeler,'model'], []) : forward_static_call_array([$modeler,'model'], [$_model]);
        
        $_o = (object) null;
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
            $_o->menu->items = self::modelOperationsMenuItems();
        
        $_o->head = 'Package Details';
        $_o->body = [''];
        $_o->body[] = (object) ['js' => 'registry.setContext({card:\'cards/package/details\',collection:\'packages\',node:\''.$_model->id.'\'});'];
        $_o->body[] = CardKitHTML::sublineBlock('Name');
        $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/package/name', [$_model->id]), $_model->name, 'settings');
        $_o->body[] = CardKitHTML::sublineBlock('Package Sequode');
        $_o->body[] = ($_model->sequode_id != 0 && SequodeModeler::exists($_model->sequode_id,'id')) ? DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/package/packageSequode', [$_model->id]), SequodeModeler::model()->name, 'settings') : ModuleForm::render($module::$registry_key,'packageSequode')[0];
        $_o->body[] = CardKitHTML::sublineBlock('Package Token');
        $_o->body[] = $_model->token;
        $_o->body[] = CardKitHTML::sublineBlock('<a target="_blank" href="/source/'.$_model->token.'">Download</a>');
        
        $_o->body[] = CardKit::nextInCollection((object) ['model_id'=>$_model->id,'details_route'=>'cards/package/details']);
        if(AccountAuthority::isSystemOwner()){
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
        $_o->menu->items =  [];
        
        $dom_id = FormInputComponent::uniqueHash('','');
        $_o->menu->items[] = [
            'css_classes'=>'automagic-card-menu-item noSelect',
            'id'=>$dom_id,
            'contents'=>'New Package',
            'js_action'=> DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject('operations/package/newPackage'))
        ];
        $_o->body = [];
        $_o->body[] = CardKit::collectionCard((object) ['collection'=>'packages','icon'=>'atom','card_route'=>'cards/package/my','details_route'=>'cards/package/details']);
        
        return $_o;
        
    }
    public static function search(){
        
        $module = static::$module;
        $_o = (object) null;
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = [];
        
        $search_components_array = ModuleForm::render($module::$registry_key,'search');
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
        $_o->body[] = CardKit::collectionCard((object) ['collection'=>'package_search','icon'=>'atom','card_route'=>'cards/package/search','details_route'=>'cards/package/details']);
        
        return $_o;
        
    }
    
    public static function myTile($user_model=null){
        
        $module = static::$module;
        $context = $module::model()->context;
        
        if($user_model == null ){
            
            $user_model = AccountModeler::model();
            
        }
        
        $_o = (object) null;
        $_o->head = 'Sequence Packages';
        $_o->size = 'xsmall';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items =  [];
        $_o->menu->item[] = CardKit::onTapEventsXHRCallMenuItem('New Package','operations/'.$context.'/newPackage');
        $_o->body = [];
        $_o->body[] = '';
        $_o->body[] = CardKit::ownedItemsCollectionTile('Package', 'Packages Created : ', $user_model);
        
        return $_o;
        
    }
    
}