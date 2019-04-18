<?php

namespace Sequode\Application\Modules\Package\Components;

use Sequode\Application\Modules\Package\Module;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\FormInput as FormInputComponent;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;

class Cards {
    
    const Module = Module::class;

    const Tiles = ['packages','search'];

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

        $_o = (object) null;

        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'packages',
            'node' => $_model->id
        ];
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = self::modelMenuItems();
        $_o->head = 'Package Details';
        $_o->body = [''];
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

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = (object) null;

        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'packages',
            'teardown' => 'function(){cards = undefined;}'
        ];
        $_o->size = 'fullscreen';
        $_o->head = 'Packages';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items =  [];
        
        $dom_id = FormInputComponent::uniqueHash('','');
        $_o->menu->items[] = [
            'css_classes'=>'automagic-card-menu-item noSelect',
            'id'=>$dom_id,
            'contents'=>'New Package',
            'js_action'=> DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject($module::xhrOperationRoute('newPackage')))
        ];
        $_o->body = [];
        $_o->body[] = CardKit::collectionCard((object) ['collection' => 'packages', 'icon' => 'atom', 'card_route' => $module::xhrCardRoute(__FUNCTION__), 'details_route' => $module::xhrCardRoute('details')]);
        
        return $_o;
        
    }

    public static function search(){

        extract((static::Module)::variables());

        $_o = (object) null;

        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'package_search',
            'teardown' => 'function(){cards = undefined;}'
        ];
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'atom-icon-background';
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
        $_o->body[] = CardKit::collectionCard((object) ['collection' => 'package_search', 'icon' => 'atom', 'card_route' => $module::xhrCardRoute('search'), 'details_route' => $module::xhrCardRoute('details')]);
        
        return $_o;
        
    }
    
}