<?php

namespace Sequode\Application\Modules\Sequode\Components;

use Sequode\View\Module\Card as ModuleCard;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\Form as FormComponent;
use Sequode\Component\FormInput as FormInputComponent;
use Sequode\Application\Modules\FormInput\Modeler as FormInputModeler;
use Sequode\Application\Modules\Sequode\Module;
use Sequode\Application\Modules\Account\Module as AccountModule;
use Sequode\Application\Modules\Account\Authority as AccountAuthority;
use Sequode\Application\Modules\Sequode\Authority as SequodeAuthority;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Sequode\Modeler as SequodeModeler;

class Cards {
    public static $module = Module::class;
    public static $tiles = ['myTile'];
    
    public static function menu(){
        $_o = (object) null;
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->menu = (object) null;
        $_o->menu->position_adjuster =  'automagic-card-menu-right-side-adjuster';
        $_o->menu->items =  array_merge(self::menuItems(),self::collectionOwnedMenuItems());
        return $_o;
    }
    public static function menuItems(){

        $module = static::$module;
        $dom_id = FormInputComponent::uniqueHash('','');
        $_o = [];
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Search Sequodes', $module::xhrCardRoute('search'));
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Favorited Sequodes', $module::xhrCardRoute('favorites'));
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('New Sequode', $module::xhrOperationRoute('newSequence'));
        return $_o;
    }
    public static function collectionOwnedMenuItems($user_model = null, $fields='id,name'){
        
        if($user_model == null ){
            $user_model = AccountModeler::model();
        }
        
        $module = static::$module;
        $operations = $module::model()->operations;
        $models = $operations::getOwnedModels($user_model, $fields, 10)->all;
        $items = [];
        if(count($models) > 0){
            $items[] = CardKit::onTapEventsXHRCallMenuItem('My Sequodes', $module::xhrCardRoute('my'));
            foreach($models as $model){
                $items[] = CardKit::onTapEventsXHRCallMenuItem($model->name, $module::xhrCardRoute('details'), [$model->id]);
            }
        }
        return $items;
        
    }
    public static function modelOperationsMenuItems($filter='', $_model = null){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
        $items = [];
        if(AccountAuthority::canView($_model)){
            $items[] = CardKit::onTapEventsXHRCallMenuItem('Details', $module::xhrCardRoute('details'), [$_model->id]);
        }
        if(AccountAuthority::isInSequodeFavorites($_model)){
            $items[] = CardKit::onTapEventsXHRCallMenuItem('Remove From Favorited', AccountModule::xhrOperationRoute('removeFromSequodeFavorites'), [$_model->id]);
        }else{
            $items[] = CardKit::onTapEventsXHRCallMenuItem('Add To Favorited', AccountModule::xhrOperationRoute('addToSequodeFavorites'), [$_model->id]);
        }
        if(SequodeAuthority::isSequence($_model)){
            
            $items[] = CardKit::onTapEventsXHRCallMenuItem('View Chart', $module::xhrCardRoute('chart'), [$_model->id]);
            
            if(AccountAuthority::canEdit($_model)){
                $items[] = CardKit::onTapEventsXHRCallMenuItem('Edit Chart', $module::xhrCardRoute('sequencer'), [$_model->id]);
            }
            if(AccountAuthority::canEdit($_model)){
                if(!SequodeAuthority::isEmptySequence($_model)){
                    $items[] = CardKit::onTapEventsXHRCallMenuItem('Empty Sequence', $module::xhrOperationRoute('emptySequence'), [$_model->id]);
                }
            }
            if(AccountAuthority::canEdit($_model)){
                if(!SequodeAuthority::isEmptySequence($_model)){
                    $items[] = CardKit::onTapEventsXHRCallMenuItem('Restore To Default', $module::xhrOperationRoute('formatSequence'), [$_model->id]);
                }
            }
            if(AccountAuthority::canCopy($_model)){
                if(!SequodeAuthority::isEmptySequence($_model)){
                    $items[] = CardKit::onTapEventsXHRCallMenuItem('Clone', $module::xhrOperationRoute('cloneSequence'), [$_model->id]);
                }
            }
            if(AccountAuthority::canEdit($_model)){
                if(!SequodeAuthority::isEmptySequence($_model)){
                    $items[] = CardKit::onTapEventsXHRCallMenuItem('Internal Forms', $module::xhrCardRoute('internalForms'), [$_model->id]);
                }
            }
            if(AccountAuthority::canDelete($_model)){
                if(SequodeAuthority::isEmptySequence($_model)){
                    $items[] = CardKit::onTapEventsXHRCallMenuItem('Delete',  $module::xhrOperationRoute('deleteSequence'), [$_model->id]);
                }
            }
        }
        
        return $items;
    }
	public static function componentSettings($type, $member, $_model = null){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'sequodes',
            'node' => $_model->id,
            'parameters' => [
                'type'
            ]
        ];
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        $_o->head = 'Component Settings';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->head = 'Component';
        $dom_id = FormInputComponent::uniqueHash('','');
        $components = ModuleForm::render($module::$registry_key, 'componentSettings', [$type, $member, $dom_id]);
        $_o->body[] = '<div id="' . $dom_id . '">';
        foreach($components as $component){
            $_o->body[] = $component;
        }
        $_o->body[] = '</div>';
        return $_o;
	}
    public static function sequode($_model = null){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'sequodes',
            'node' => $_model->id
        ];
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->head = 'Component';
        $_o->body = [];
        foreach(ModuleForm::render($module::$registry_key, 'sequode') as $component){
            $_o->body[] = $component->html;
        }
        return $_o;
	}

    public static function details( $_model = null){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'sequodes',
            'node' => $_model->id
        ];
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        
        $_o->head = 'Sequode Details';
        $_o->size = 'large';
        $_o->body = [''];
        $_o->body[] = CardKitHTML::sublineBlock('Name');
        $_o->body[] = (AccountAuthority::canEdit($_model))
            ? DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject($module::xhrFormRoute('name'), [$_model->id]), $_model->name, 'settings')
            : $_model->name;
        $_o->body[] = CardKitHTML::sublineBlock('Description');
        $text = $_model->detail->description ?: 'Sequode needs description.';
        $_o->body[] = (AccountAuthority::canEdit($_model)) ? DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject($module::xhrFormRoute('description'), [$_model->id]), $text, 'settings') : $text;
        
        if(SequodeAuthority::isCode() && $_model->owner_id == 8){
            $dom_id = FormInputComponent::uniqueHash('','');
            $html = $js = [];
            $html = CardKitHTML::sublineBlock('More Info');
            $js = DOMElementKitJS::onTapEvents($dom_id, 'var win = window.open(\'http://php.net/'.$_model->name.'\', \'_blank\'); win.focus();');
            $_o->body[] = (object) ['html' => $html, 'js' => $js];
        }
        if(SequodeAuthority::isSequence() && !SequodeAuthority::isEmptySequence()){
            $_o->body[] = CardKit::onTapEventsXHRCallButton('View Chart', $module::xhrCardRoute('chart'), [$_model->id]);
        } 
        if(SequodeAuthority::isSequence() && AccountAuthority::canEdit($_model)){
            $_o->body[] =  CardKit::onTapEventsXHRCallButton('Edit Chart', $module::xhrCardRoute('sequencer'), [$_model->id]);
        }
        if(SequodeAuthority::isSequence()){
            $_o->body[] = CardKitHTML::sublineBlock('Sequence');
            $sequence = $_model->sequence;
            $model_object_cache = [];
            if(!SequodeAuthority::isEmptySequence($_model)){
                foreach($sequence as $loop_sequence_key => $loop_model_id){
                    if(!array_key_exists($loop_model_id, $model_object_cache)){
                        $model_object_cache[$loop_model_id] = (new SequodeModeler::$model)->exists($loop_model_id,'id');
                    }
                    $text = '('.($loop_sequence_key+1).') '.$model_object_cache[$loop_model_id]->name;
                    $_o->body[] = (AccountAuthority::canEdit($_model)) ? DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject($module::xhrCardRoute('internalPositionForms'), [$_model->id, $loop_sequence_key]), $text, 'settings') : $text;
                }
            }else{
                    $_o->body[] = 'Sequode is empty.';   
            }
            
        }
        if(AccountAuthority::isSystemOwner()){
            $_o->body[] = CardKitHTML::sublineBlock('Use Policy');
            $text = (SequodeAuthority::isShared()) ? 'Public Use' : 'System Restricted Use';
            $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject($module::xhrFormRoute('sharing'), [$_model->id]), $text, 'atom');
        }
        if(SequodeAuthority::isSequence() && !SequodeAuthority::isEmptySequence()){
            $_o->body[] = CardKitHTML::sublineBlock('Palettes Menu Visibility');
            $text = (SequodeAuthority::isPalette()) ? 'Shown in Palettes Menu' : 'Hidden from Palettes Menu';
            $_o->body[] = (AccountAuthority::canEdit($_model)) ? DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject($module::xhrFormRoute('updateIsPalette'), [$_model->id]), $text, 'settings') : $text;
        } 
        foreach(['input','property'] as $type){
            switch($type){
                case 'input':                            
                    $type_title = 'Inputs';
                    $type_object = $_model->input_object;
                    $type_object_detail = $_model->input_object_detail;
                    $type_form_object = $_model->input_form_object;
                break;
                case 'property':
                    $type_title = 'Properties';
                    $type_object = $_model->property_object;
                    $type_object_detail = $_model->property_object_detail;
                    $type_form_object = $_model->property_form_object;
                break;
            }
        
            if($type_object != (object) null){
                $_o->body[] = CardKitHTML::sublineBlock($type_title);
                foreach($type_object as $member => $value){
                    $_o->body[] = $member . ' (' . $type_object_detail->$member->type. ') ' . (($type_object_detail->$member->required == true) ? 'required' : 'optional');
                    FormInputModeler::exists($type_form_object->$member->Component,'name');
                    $text = 'Form Component : '. FormInputModeler::model()->printable_name;
                    $_o->body[] = (AccountAuthority::canEdit($_model)) ? DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject($module::xhrCardRoute('componentSettings'), [FormComponent::jsQuotedValue($type), FormComponent::jsQuotedValue($member), $_model->id]), $text, 'settings') : $text;
                }
            }
        }
        if($_model->output_object != (object) null){
            $_o->body[] = CardKitHTML::sublineBlock('Outputs');
            foreach($_model->output_object as $member => $value){
                $_o->body[] = $member . ' (' . $_model->output_object_detail->$member->type. ')';
            }
            $_o->body[] = '';
        }
        
        $_o->body[] = CardKit::nextInCollection((object) ['model_id' => $_model->id, 'details_route' => $module::xhrCardRoute('details')]);
        
        if(AccountAuthority::isSystemOwner()){
            $_o->body[] = CardKitHTML::modelId($_model);
        }

        return $_o;
    }
    public static function internalForms( $_model = null){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
        
        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'sequodes',
            'node' => $_model->id
        ];
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->head = $_model->name;
        $_o->body = [];
        foreach($_model->sequence as $loop_sequence_key => $loop_model_id){
            $_o->body[] = ModuleCard::render($module::$registry_key,'internalPositionForms', [$loop_sequence_key]);
        }
        if(AccountAuthority::isSystemOwner()){
            $_o->body[] = CardKitHTML::modelId($_model);
        }
        return $_o;
    }
    public static function internalPositionForms($position, $_model = null){
        $position = intval($position);
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'sequodes',
            'node' => $_model->id
        ];
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        $sequence = $_model->sequence;
        if(!isset($sequence[$position])){ return; }
        $sequence_model_id = $sequence[$position];
        $sequence_model = new SequodeModeler::$model;
        $sequence_model->exists($sequence_model_id,'id');

        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->head = $sequence_model->name;
        $_o->body = [];

        $types = ['input', 'property'];
        $type_labels = ['input' => 'Inputs', 'property' => 'Properties'];
        $detail_object_members = ['input' => 'input_object_detail', 'property' => 'property_object_detail'];
        $maps = ['input' => $_model->input_object_map, 'property' => $_model->property_object_map];
        $default_maps = ['input' => $_model->default_input_object_map, 'property' => $_model->default_property_object_map];
        
        $possible_components = [];
        
        foreach($types as $type){
            $map = $maps[$type];
            $default_map = $default_maps[$type];
            $detail_object_member = $detail_object_members[$type];
            foreach($default_map as $map_key => $location_object){
                if($map_key == 0){ continue; }
                $sequence_key = $location_object->Key - 1;
                if($sequence_key < $position){ continue; }
                if($sequence_key > $position){ break; }
                $member = $location_object->Member;
                $component_object = (object) null;
                $component_object->map_key = $map_key;
                $component_object->type = $type;
                $component_object->sequence_key = $sequence_key;
                $component_object->member = $member;
                $component_object->required = $sequence_model->$detail_object_member->$member->required;
                $component_object->connected = (!(
                    $map[$map_key]->Key == $location_object->Key
                    && $map[$map_key]->Stack == $location_object->Stack
                )) ? true : false;
                $component_object->value_set = (
                    $component_object->connected == false
                    && !empty($map[$map_key]->Value)
                ) ? true : false;
                $possible_components[] = $component_object;
            }
        }
        
        foreach($possible_components as $component){
            if($type_labels[$component->type] != false){
                $_o->body[] = CardKitHTML::sublineBlock($type_labels[$component->type]);
                $type_labels[$component->type] = false;
            }
            if(($component->connected == true)){
                $text = $component->member;
                $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject($module::xhrFormRoute('component'), [FormComponent::jsQuotedValue($component->type), $_model->id, $component->map_key]), $text, 'settings');
            }elseif($component->required == false && $component->value_set == false){ 
                $text = $component->member;
                $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject($module::xhrFormRoute('component'), [FormComponent::jsQuotedValue($component->type), $_model->id, $component->map_key]), $text, 'settings');
            }else{
                $components_array = ModuleForm::render($module::$registry_key,'component', [$component->type, $component->map_key, $_model]);
                foreach($components_array as $component_object){
                    $_o->body[] = $component_object;
                }
            }
        }
        
        return $_o;
        
    }
    public static function sequencer( $_model = null){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);

        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'sequodes',
            'node' => $_model->id
        ];
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->head = 'Sequode Chart &gt; Edit &gt; '.$_model->name;
        
        $items = [];
        $items[] = [
            'css_classes'=>'automagic-card-menu-item-label noSelect',
            'contents'=>'Select Palette'
        ];
        $dom_id = FormInputComponent::uniqueHash('','');
        $items[] = [
            'css_classes'=>'automagic-card-menu-item noSelect',
            'id'=>$dom_id,
            'js_action'=> 'new XHRCall({route:\''.$module::xhrFormRoute('selectPalette').'\',inputs:['.FormComponent::jsQuotedValue($dom_id).']});'
        ];
        $_o->menu->items = array_merge($items,$_o->menu->items);
        $_o->body = [];
        $dom_id = FormInputComponent::uniqueHash('','');
        $html = $js = [];
        $html[] = '<div class="SequencerStageContainer" id="'.$dom_id.'chart"></div>';
        $js[] = 'var sequencer;';
        $js[] = 'sequencer = new Sequencer();';
        $js[] = 'sequencer.stage = shapesKit.stage({ container: \''.$dom_id.'chart\', width: $(window).width(), height: $(window).height() });';
        $js[] = 'registry.subscribeToUpdates({type:\'context\', collection:\'sequodes\', key:true, call: sequencer.run});';
        $js[] = 'registry.subscribeToUpdates({type:\'context\', collection:\'palette\', call: sequencer.palette.run});';
        $js[] = 'registry.fetch({collection:\'sequodes\',key:'.$_model->id.'});';
        $_o->body[] = (object) ['html' => implode('', $html), 'js' => implode(' ', $js)];
        
        return $_o;
        
    }
    public static function chart( $_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $_model = forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
        
        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'sequodes',
            'node' => $_model->id
        ];
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->head = 'Sequode Chart &gt; View &gt; '.$_model->name;
        $_o->body = [];
        $html = $js = [];
        $html[] = '<div class="SequencerStageContainer" id="'.$dom_id.'chart"></div>';
        $js[] = 'var sequencer;';
        $js[] = 'sequencer = new Sequencer();';
        $js[] = 'sequencer.default_events = false;';
        $js[] = 'sequencer.stage = shapesKit.stage({ container: \''.$dom_id.'chart\', width: $(window).width(), height: $(window).height() });';
        $js[] = 'registry.subscribeToUpdates({type:\'context\', collection:\'sequodes\', key:true, call: sequencer.run});';
        $js[] = 'registry.fetch({collection:\'sequodes\',key:'.$_model->id.'});';
        $_o->body[] = (object) ['html' => implode('', $html), 'js' => implode(' ', $js)];
        
        return $_o;
        
    }
    public static function search($_model = null){
        
        $module = static::$module;
        
        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'sequode_search',
            'teardown' => 'function(){cards = undefined;}'
        ];
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
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
        $_o->body[] = CardKit::collectionCard((object) ['collection' => 'sequode_search', 'icon' => 'sequode', 'card_route' => $module::xhrCardRoute('search'), 'details_route' => $module::xhrCardRoute('details')]);
        
        return $_o;
        
    }
    public static function my(){

        $module = static::$module;
        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'my_sequodes',
            'teardown' => 'function(){cards = undefined;}'
        ];
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = [];
        
        $_o->head = 'My Sequodes';
        
        $dom_id = FormInputComponent::uniqueHash('','');
        $_o->menu->items[] = [
            'css_classes'=>'automagic-card-menu-item noSelect',
            'id'=>$dom_id,
            'contents'=>'New Sequode',
            'js_action'=> DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject($module::xhrOperationRoute('newSequence')))
        ];
        
        $_o->body = [];
        $_o->body[] = CardKit::collectionCard((object) ['collection' => 'my_sequodes', 'icon' => 'sequode', 'card_route' => $module::xhrCardRoute('my'), 'details_route' => $module::xhrCardRoute('details')]);

        return $_o;
        
    }
    public static function favorites(){

        $module = static::$module;
        $_o = (object) null;
        $_o->context = (object)[
            'card' => $module::xhrCardRoute(__FUNCTION__),
            'collection' => 'sequode_favorites',
            'teardown' => 'function(){cards = undefined;}'
        ];
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = [];
        
        $_o->head = 'Sequode Favorites';
        
        $dom_id = FormInputComponent::uniqueHash('','');
        $_o->menu->items[] = [
            'css_classes'=>'automagic-card-menu-item noSelect',
            'id'=>$dom_id,
            'contents'=>'Empty Favorites',
            'js_action'=> DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject(AccountModule::xhrOperationRoute('emptySequodeFavorites'),[]/*,'function(){registry.fetch({collection:\'sequode_favorites\'});}' */))
        ];
        
        $_o->body = [];
        $_o->body[] = CardKit::collectionCard((object) ['collection'=>'sequode_favorites','icon'=>'sequode','card_route' => $module::xhrCardRoute('favorites'),'details_route' => $module::xhrCardRoute('details')]);
        
        return $_o;
        
    }
    
    public static function myTile($user_model=null){

        $module = static::$module;
        
        if($user_model == null ){
            
            $user_model = AccountModeler::model();
            
        }
        
        $_o = (object) null;
        $_o->head = 'Sequodes';
        $_o->size = 'xsmall';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items =  [];
        $_o->menu->item[] = CardKit::onTapEventsXHRCallMenuItem('New Sequode',$module::xhrOperationRoute('newSequence'));
        $_o->body = [];
        $_o->body[] = '';
        $_o->body[] = CardKit::ownedItemsCollectionTile($module::$registry_key, $user_model, 'Sequodes : ');
        
        
        return $_o;
        
    }
}