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
    
    const Module = Module::class;
    
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

        extract((static::Module)::variables());

        $_o = [];

        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Search Sessions', $module::xhrCardRoute('search'));
        
        return $_o;
        
    }

    public static function modelOperationsMenuItems($filter='', $_model = null){

        extract((static::Module)::variables());

        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = [];

        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Details', $module::xhrCardRoute('details'), [$modeler::model()->id]);
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Delete Session', $module::xhrOperationRoute('destroy'), [$modeler::model()->id]);

        return $_o;
    }

    public static function details($_model=null){

        extract((static::Module)::variables());
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
        
        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'sessions',
            'node' => $modeler::model()->id
        ];
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'session-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items =  static::menuItems() + static::modelOperationsMenuItems();
        
        $items[] = CardKit::onTapEventsXHRCallMenuItem('Delete Session', $module::xhrOperationRoute('destroy'), [$modeler::model()->id]);

        $_o->body = [];

        $_o->body[] = CardKit::nextInCollection((object) ['model_id' => $modeler::model()->id, 'details_route' => $module::xhrCardRoute('details')]);
        
        $dom_id = FormInputComponent::uniqueHash('','');
        $html = $js = [];
        $js[] = DOMElementKitJS::documentEventOff('keydown');
        $js[] = '$(document).on(\'keydown\',(function(e){';
        
        $js[] = 'if (e.keyCode == 66){';
        $js[] = 'new XHRCall({route:"'.$module::xhrOperationRoute('blockIP').'",inputs:['.$modeler::model()->id.']});';
        $js[] = '}';
        
        $js[] = 'if(next_id != \''.$modeler::model()->id.'\'){';
        $js[] = 'if (e.keyCode == 39){';
        $js[] = 'new XHRCall({route:"'.$module::xhrCardRoute('details').'",inputs:[next_id]});';
        $js[] = '}';
        $js[] = '}';
        if($modeler::model()->session_id != $operations::getCookieValue()) {
            $js[] = 'if (e.keyCode == 46){';
            $js[] = 'new XHRCall({route:\''.$module::xhrOperationRoute('destroy').'\',inputs: [' . $modeler::model()->id . '],done_callback:function(){ new XHRCall({route:\''.$module::xhrCardRoute('details').'\',inputs:[next_id]});} });';
            $js[] = '}';
        }
        $js[] = '}';
        
        $js[] = '}));';

        if($modeler::model()->session_id === $operations::getCookieValue()) {
            $_o->body[] = CardKitHTML::sublineBlock('This your current session');
        }

        $_o->body[] = CardKitHTML::sublineBlock('Session Id');
        $_o->body[] = $modeler::model()->session_id;
        $_o->body[] = CardKitHTML::sublineBlock('Name');
        $_o->body[] = $modeler::model()->name;
        $_o->body[] = CardKitHTML::sublineBlock('Ip Address');
        $_o->body[] = $modeler::model()->ip_address;
        $_o->body[] = CardKitHTML::sublineBlock('Data');
        $_o->body[] = '<textarea style="width:20em; height:10em;">'.PHPClosure::export($modeler::model()->session_data).'</textarea>';
        $location = false; //geoip_record_by_name($_model->ip_address);
        if ($location) {
        $_o->body[] = CardKitHTML::sublineBlock('Geo Location');
        $_o->body[] = $location['city'].((!empty($location['region'])) ? ' '.$location['region'] : ''). ', '. $location['country_name'].((!empty($location['postal_code'])) ? ', '.$location['postal_code'] : '');
            
        }
        $_o->body[] = CardKitHTML::sublineBlock('Session Started');
        $_o->body[] = date('g:ia \o\n l jS F Y',$modeler::model()->session_start);
        $_o->body[] = CardKitHTML::sublineBlock('Last Sign In');
        if($modeler::model()->session_id != $operations::getCookieValue()) {
            $_o->body[] = CardKit::deleteInCollection((object)['route' => $module::xhrOperationRoute('destroy'), 'model_id' => $modeler::model()->id]);
        }
        $_o->body[] = CardKitHTML::modelId($modeler::model());
        
        return $_o;
        
    }

    public static function search($_model = null){

        extract((static::Module)::variables());
        
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
        $_o->body[] = CardKit::collectionCard((object) ['collection'=>'session_search', 'icon'=>'atom', 'card_route'=>$module::xhrCardRoute('search'), 'details_route'=>$module::xhrCardRoute('details')]);
        
        return $_o;
        
    }

}