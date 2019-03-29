<?php

namespace Sequode\Application\Modules\Session\Components;

use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\FormInput as FormInputComponent;
use Sequode\View\Export\PHPClosure;
use Sequode\Application\Modules\Session\Module;
    
class Cards {
    
    public static $module = Module::class;
    
    public static function menu(){
        
        $_o = (object) null;
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'session-icon-background';
        $_o->menu = (object) null;
        $_o->menu->position_adjuster =  'automagic-card-menu-right-side-adjuster';
        $_o->menu->items =  self::menuItems();
        
        return $_o;
        
    }

    public static function menuItems(){

        $module = static::$module;
        $_o = [];
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Search Sessions', $module::xhrCardRoute('search'));
        
        return $_o;
        
    }

    public static function modelOperationsMenuItems($filter='', $_model = null){

        $module = static::$module;
        $modeler = $module::model()->modeler;

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = [];
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Details', $module::xhrCardRoute('details'), [$modeler::model()->id]);
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Delete Session', $module::xhrOperationRoute('destroy'), [$modeler::model()->id]);

        return $_o;
    }

    public static function details($_model=null){

        $module = static::$module;
        $modeler = $module::model()->modeler;
        $operations = $module::model()->operations;

        
        $_model = forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
        
        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'sessions',
            'node' => $_model->id
        ];
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'session-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items =  static::menuItems() + static::modelOperationsMenuItems();
        
        $items[] = CardKit::onTapEventsXHRCallMenuItem('Delete Session', $module::xhrOperationRoute('destroy'), [$_model->id]);
        
        $_o->body[] = CardKit::nextInCollection((object) ['model_id' => $_model->id, 'details_route' => $module::xhrCardRoute('details')]);
        
        $_o->body = [];
        $dom_id = FormInputComponent::uniqueHash('','');
        $html = $js = [];
        $js[] = DOMElementKitJS::documentEventOff('keydown');
        $js[] = '$(document).on(\'keydown\',(function(e){';
        
        $js[] = 'if (e.keyCode == 66){';
        $js[] = 'new XHRCall({route:"'.$module::xhrOperationRoute('blockIP').'",inputs:['.$_model->id.']});';
        $js[] = '}';
        
        $js[] = 'if(next_id != \''.$_model->id.'\'){';
        $js[] = 'if (e.keyCode == 39){';
        $js[] = 'new XHRCall({route:"'.$module::xhrCardRoute('details').'",inputs:[next_id]});';
        $js[] = '}';
        $js[] = '}';
        if($_model->session_id != $operations::getCookieValue()) {
            $js[] = 'if (e.keyCode == 46){';
            $js[] = 'new XHRCall({route:\''.$module::xhrOperationRoute('destroy').'\',inputs: [' . $_model->id . '],done_callback:function(){ new XHRCall({route:\''.$module::xhrCardRoute('details').'\',inputs:[next_id]});} });';
            $js[] = '}';
        }
        $js[] = '}';
        
        $js[] = '}));';

        if($_model->session_id === $operations::getCookieValue()) {
            $_o->body[] = CardKitHTML::sublineBlock('This your current session');
        }

        $_o->body[] = CardKitHTML::sublineBlock('Session Id');
        $_o->body[] = $_model->session_id;
        $_o->body[] = CardKitHTML::sublineBlock('Name');
        $_o->body[] = $_model->name;
        $_o->body[] = CardKitHTML::sublineBlock('Ip Address');
        $_o->body[] = $_model->ip_address;
        $_o->body[] = CardKitHTML::sublineBlock('Data');
        $_o->body[] = '<textarea style="width:20em; height:10em;">'.PHPClosure::export($_model->session_data).'</textarea>';
        $location = false; //geoip_record_by_name($_model->ip_address);
        if ($location) {
        $_o->body[] = CardKitHTML::sublineBlock('Geo Location');
        $_o->body[] = $location['city'].((!empty($location['region'])) ? ' '.$location['region'] : ''). ', '. $location['country_name'].((!empty($location['postal_code'])) ? ', '.$location['postal_code'] : '');
            
        }
        $_o->body[] = CardKitHTML::sublineBlock('Session Started');
        $_o->body[] = date('g:ia \o\n l jS F Y',$_model->session_start);
        $_o->body[] = CardKitHTML::sublineBlock('Last Sign In');
        if($_model->session_id != $operations::getCookieValue()) {
            $_o->body[] = CardKit::deleteInCollection((object)['route' => $module::xhrOperationRoute('destroy'), 'model_id' => $_model->id]);
        }
        $_o->body[] = CardKitHTML::modelId($_model);
        
        return $_o;
        
    }
    public static function search($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
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
        $_o->body[] = CardKit::collectionCard((object) ['collection'=>'session_search', 'icon'=>'atom', 'card_route'=>$module::xhrCardRoute('search'), 'details_route'=>$module::xhrCardRoute('details')]);
        
        return $_o;
        
    }
}