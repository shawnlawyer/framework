<?php

namespace Sequode\Application\Modules\Session\Components;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\CardKit as CardKit;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\FormInput\FormInput as FormInputComponent;

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
        $_o = array();
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Search Sessions','cards/session/search');
        return $_o;
    }
    public static function details($_model=null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $_model = ($_model == null ) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'), array($_model));
        
        $_o = (object) null;
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'session-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items =  array();
        
        $items[] = CardKit::onTapEventsXHRCallMenuItem('Delete Session','cards/session/delete',array($_model->id));
        
        $_o->body[] = CardKit::nextInCollection((object) array('model_id'=>$_model->id,'details_route'=>'cards/session/details'));
        
        $_o->body = array();
        $dom_id = FormInputComponent::uniqueHash('','');
        $html = $js = array();
        $js[] = DOMElementKitJS::documentEventOff('keydown');
        $js[] = '$(document).on(\'keydown\',(function(e){';
        
        $js[] = 'if (e.keyCode == 66){';
        
        $js[] = 'new XHRCall({route:"operations/session/blockIP",inputs:['.$_model->id.']});';
        $js[] = '}';
        
        $js[] = 'if(next_id != \''.$_model->id.'\'){';
        
        $js[] = 'if (e.keyCode == 39){';
        $js[] = 'new XHRCall({route:"cards/session/details",inputs:[next_id]});';
        $js[] = '}';
        
        $js[] = 'if (e.keyCode == 46){';
        $js[] = 'new XHRCall({route:\'operations/session/delete\',inputs: ['.$_model->id.'],done_callback:function(){ new XHRCall({route:\'cards/session/details\',inputs:[next_id]});} });';
        $js[] = '}';
        
        $js[] = '}else{';
        $js[] = 'if (e.keyCode == 46){';
        $js[] = 'new XHRCall({route:\'operations/session/delete\',inputs: ['.$_model->id.']});';
        $js[] = '}';
        
        $js[] = '}';
        
        $js[] = '}));';
        
        
        $_o->body[] = CardKitHTML::sublineBlock('Username');
        $_o->body[] = $_model->username;
        $_o->body[] = CardKitHTML::sublineBlock('Ip Address');
        $_o->body[] = $_model->ip_address;
        $_o->body[] = CardKitHTML::sublineBlock('Data');
        $_o->body[] = '<textarea style="width:20em; height:10em;">'.$_model->session_data.'</textarea>';
        $location = geoip_record_by_name($_model->ip_address);
        if ($location) {
        $_o->body[] = CardKitHTML::sublineBlock('Geo Location');
        $_o->body[] = $location['city'].((!empty($location['region'])) ? ' '.$location['region'] : ''). ', '. $location['country_name'].((!empty($location['postal_code'])) ? ', '.$location['postal_code'] : '');
            
        }
        $_o->body[] = CardKitHTML::sublineBlock('Session Started');
        $_o->body[] = date('g:ia \o\n l jS F Y',$_model->session_start);
        $_o->body[] = CardKitHTML::sublineBlock('Last Sign In');
        $_o->body[] = CardKit::deleteInCollection((object) array('route'=>'operations/session/delete','model_id'=>$_model->id));
        $_o->body[] = CardKitHTML::modelId($_model);
        return $_o;
    }
    public static function search($_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $_o = (object) null;
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'session-icon-background';
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
        $_o->body[] = CardKit::collectionCard((object) array('collection'=>'session_search','icon'=>'atom','card_route'=>'cards/session/search','details_route'=>'cards/session/details'));
        return $_o;
    }
}