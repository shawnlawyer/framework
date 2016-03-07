<?php

namespace Sequode\Application\Modules\Sequode\Components;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Card as ModuleCard;
use Sequode\View\Module\Form as ModuleForm;
use Sequode\Component\Card\CardKit as CardKit;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Component\FormInput\FormInput as FormInputComponent;

use Sequode\Application\Modules\Sequode\Module;

class Cards {
    public static $module = Module::class;
    public static $tiles = array('myTile');
    
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
        $dom_id = FormInputComponent::uniqueHash('','');
        $_o = array();
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Search Sequodes','cards/sequode/search');
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('Favorited Sequodes','cards/sequode/favorites');
        $_o[] = CardKit::onTapEventsXHRCallMenuItem('New Sequode','operations/sequode/newSequence');
        return $_o;
    }
    public static function collectionOwnedMenuItems($user_model = null, $fields='id,name'){
        
        if($user_model == null ){
            $user_model = \Sequode\Application\Modules\Account\Modeler::model();
        }
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $operations = $module::model($package)->operations;
        $context = $module::model($package)->context;
        $models = $operations::getOwnedModels($user_model, $fields, 10)->all;
        $items = array();
        if(count($models) > 0){
            $items[] = CardKit::onTapEventsXHRCallMenuItem('My Sequodes', 'cards/'.$context.'/my');
            foreach($models as $model){
                $items[] = CardKit::onTapEventsXHRCallMenuItem($model->name, 'cards/'.$context.'/details', array($model->id));
            }
        }
        return $items;
        
    }
    public static function modelOperationsMenuItems($filter='', $_model = null){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = ($_model == null ) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'), array($_model));
        $items = array();
        if(\Sequode\Application\Modules\Account\Authority::canView($_model)){
            $items[] = CardKit::onTapEventsXHRCallMenuItem('Details','cards/sequode/details', array($_model->id));
        }
        if(\Sequode\Application\Modules\Account\Authority::isInSequodeFavorites($_model)){
            $items[] = CardKit::onTapEventsXHRCallMenuItem('Remove From Favorited','operations/account/removeFromSequodeFavorites', array($_model->id));
        }else{
            $items[] = CardKit::onTapEventsXHRCallMenuItem('Add To Favorited','operations/account/addToSequodeFavorites', array($_model->id));
        }
        if(\Sequode\Application\Modules\Sequode\Authority::isSequence($_model)){
            
            $items[] = CardKit::onTapEventsXHRCallMenuItem('View Chart','cards/sequode/chart', array($_model->id));
            
            if(\Sequode\Application\Modules\Account\Authority::canEdit($_model)){
                $items[] = CardKit::onTapEventsXHRCallMenuItem('Edit Chart','cards/sequode/sequencer', array($_model->id));
            }
            if(\Sequode\Application\Modules\Account\Authority::canEdit($_model)){
                if(!\Sequode\Application\Modules\Sequode\Authority::isEmptySequence($_model)){
                    $items[] = CardKit::onTapEventsXHRCallMenuItem('Empty Sequence','operations/sequode/emptySequence', array($_model->id));
                }
            }
            if(\Sequode\Application\Modules\Account\Authority::canEdit($_model)){
                if(!\Sequode\Application\Modules\Sequode\Authority::isEmptySequence($_model)){
                    $items[] = CardKit::onTapEventsXHRCallMenuItem('Restore To Default','operations/sequode/formatSequence', array($_model->id));
                }
            }
            if(\Sequode\Application\Modules\Account\Authority::canCopy($_model)){
                if(!\Sequode\Application\Modules\Sequode\Authority::isEmptySequence($_model)){
                    $items[] = CardKit::onTapEventsXHRCallMenuItem('Clone','operations/sequode/cloneSequence', array($_model->id));
                }
            }
            if(\Sequode\Application\Modules\Account\Authority::canEdit($_model)){
                if(!\Sequode\Application\Modules\Sequode\Authority::isEmptySequence($_model)){
                    $items[] = CardKit::onTapEventsXHRCallMenuItem('Internal Forms','cards/sequode/internalForms', array($_model->id));
                }
            }
            if(\Sequode\Application\Modules\Account\Authority::canDelete($_model)){
                if(\Sequode\Application\Modules\Sequode\Authority::isEmptySequence($_model)){
                    $items[] = CardKit::onTapEventsXHRCallMenuItem('Delete','operations/sequode/deleteSequence', array($_model->id));
                }
            }
        }
        
        return $items;
    }
	public static function componentSettings($type, $member, $_model = null){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = ($_model == null ) ? forward_static_call_array(array($modeler, 'model'), array()) : forward_static_call_array(array($modeler,'model'), array($_model));
        
        $_o = (object) null;
        $_o->head = 'Component Settings';
        $_o->icon_type = 'card-icon';
        $_o->icon_background = 'atom-icon-background';
        $_o->size = 'medium';
        $dom_id = FormInputComponent::uniqueHash('','');
        $components = ModuleForm::render($module::$registry_key, 'componentSettings', array($type, $member, $dom_id));
        $_o->body = array();
        $_o->body[] = '<div id="' . $dom_id . '">';
        foreach($components as $component){
            $_o->body[] = $component;
        }
        $_o->body[] = '</div>';
        return $_o;
	}
    public static function sequode($dom_id, $_model = null){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = ($_model == null ) ? forward_static_call_array(array($modeler, 'model'), array()) : forward_static_call_array(array($modeler, 'model'), array($_model));
        
        $_o = (object) null;
        $_o->head = 'Component';
        $components = ModuleForm::render($module::$registry_key, 'sequode');
        $_o->body = array();
        foreach($components as $component){
            $_o->body[] = $component->html;
        }
        return $_o;
	}   
    public static function details( $_model = null){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = ($_model == null ) ? forward_static_call_array(array($modeler, 'model'), array()) : forward_static_call_array(array($modeler, 'model'), array($_model));
        
        $_o = (object) null;
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        
        $_o->head = 'Sequode Details';
        $_o->size = 'large';
        $_o->body = array('');
        
        $_o->body[] = (object) array('js' => 'registry.setContext({card:\'cards/sequode/details\',collection:\'sequodes\',node:\''.$_model->id.'\'});');
        $input_object = json_decode($_model->input_object);
        $property_object = json_decode($_model->property_object);
        $output_object = json_decode($_model->output_object);
        $input_object_detail = json_decode($_model->input_object_detail);
        $property_object_detail = json_decode($_model->property_object_detail);
        $output_object_detail = json_decode($_model->output_object_detail);
        $input_form_object = json_decode($_model->input_form_object);
        $property_form_object = json_decode($_model->property_form_object);
        
        $_o->body[] = CardKitHTML::sublineBlock('Name');
        $text = $_model->name;
        $_o->body[] = (\Sequode\Application\Modules\Account\Authority::canEdit()) ? DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/sequode/name', array($_model->id)), $text, 'settings') : $text;
        
        $_o->body[] = CardKitHTML::sublineBlock('Description');
        $text = json_decode($_model->detail)->description;
        $text = (!empty($text)) ? $text : 'Sequode needs description.';
        $_o->body[] = (\Sequode\Application\Modules\Account\Authority::canEdit()) ? DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/sequode/description', array($_model->id)), $text, 'settings') : $text;
        
        if(\Sequode\Application\Modules\Sequode\Authority::isCode() && $_model->owner_id == 8){
            $dom_id = FormInputComponent::uniqueHash('','');
            $html = $js = array();
            $html = '<div class="subline" id="'.$dom_id.'">More info</div>';
            $js = DOMElementKitJS::onTapEvents($dom_id, 'var win = window.open(\'http://php.net/'.$_model->name.'\', \'_blank\'); win.focus();');
            $_o->body[] = (object) array('html' => $html, 'js' => $js);
        }
        if(\Sequode\Application\Modules\Sequode\Authority::isSequence() && !\Sequode\Application\Modules\Sequode\Authority::isEmptySequence()){
            $_o->body[] = CardKit::onTapEventsXHRCallButton('View Chart','cards/sequode/chart', array($_model->id));
        } 
        if(\Sequode\Application\Modules\Sequode\Authority::isSequence() && \Sequode\Application\Modules\Account\Authority::canEdit($_model)){
            $_o->body[] =  CardKit::onTapEventsXHRCallButton('Edit Chart','cards/sequode/sequencer', array($_model->id));
        }
        if(\Sequode\Application\Modules\Sequode\Authority::isSequence()){
            $_o->body[] = CardKitHTML::sublineBlock('Sequence');
            $sequence = json_decode($_model->sequence);
            $model_object_cache = array();
            if(!\Sequode\Application\Modules\Sequode\Authority::isEmptySequence($_model)){
                foreach($sequence as $loop_sequence_key => $loop_model_id){
                    if(!array_key_exists($loop_model_id, $model_object_cache)){
                        $model_object_cache[$loop_model_id] = new \Sequode\Application\Modules\Sequode\Modeler::$model;
                        $model_object_cache[$loop_model_id]->exists($loop_model_id,'id');
                    }
                    $text = '('.($loop_sequence_key+1).') '.$model_object_cache[$loop_model_id]->name;
                    $_o->body[] = (\Sequode\Application\Modules\Account\Authority::canEdit()) ? DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('cards/sequode/internalPositionForms', array($_model->id, $loop_sequence_key)), $text, 'settings') : $text;
                }
            }else{
                    $_o->body[] = 'Sequode is empty.';   
            }
            
        }
        if(\Sequode\Application\Modules\Account\Authority::isSystemOwner()){
            $_o->body[] = CardKitHTML::sublineBlock('Use Policy');
            $text = (\Sequode\Application\Modules\Sequode\Authority::isShared()) ? 'Public Use' : 'System Restricted Use';
            $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/sequode/sharing', array($_model->id)), $text, 'atom');
        }
        if(\Sequode\Application\Modules\Sequode\Authority::isSequence() && !\Sequode\Application\Modules\Sequode\Authority::isEmptySequence()){
            $_o->body[] = CardKitHTML::sublineBlock('Palettes Menu Visibility');
            $text = (\Sequode\Application\Modules\Sequode\Authority::isPalette()) ? 'Shown in Palettes Menu' : 'Hidden from Palettes Menu';
            $_o->body[] = (\Sequode\Application\Modules\Account\Authority::canEdit($_model)) ? DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/sequode/updateIsPalette', array($_model->id)), $text, 'settings') : $text;
        } 
        foreach(array('input','property') as $type){
            switch($type){
                case 'input':                            
                    $type_title = 'Inputs';
                    $type_object = $input_object;
                    $type_object_detail = $input_object_detail;
                    $type_form_object = $input_form_object;
                break;
                case 'property':
                    $type_title = 'Properties';
                    $type_object = $property_object;
                    $type_object_detail = $property_object_detail;
                    $type_form_object = $property_form_object;
                break;
            }
        
            if($type_object != (object) null){
                $_o->body[] = CardKitHTML::sublineBlock($type_title);
                foreach($type_object as $member => $value){
                    $_o->body[] = $member . ' (' . $type_object_detail->$member->type. ') ' . (($type_object_detail->$member->required == true) ? 'required' : 'optional');
                    FormInputComponent::exists($type_form_object->$member->Component,'name');
                    $text = 'Form Component : '. FormInputComponent::model()->printable_name;
                    $_o->body[] = (\Sequode\Application\Modules\Account\Authority::canEdit()) ? DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('cards/sequode/componentSettings', array(\Sequode\Component\Form\Form::jsQuotedValue($type), \Sequode\Component\Form\Form::jsQuotedValue($member), $_model->id)), $text, 'settings') : $text;
                }
            }
        }
        if($output_object != (object) null){
            $_o->body[] = CardKitHTML::sublineBlock('Outputs');
            foreach($output_object as $member => $value){
                $_o->body[] = $member . ' (' . $output_object_detail->$member->type. ')';
            }
            $_o->body[] = '';
        }
        
        $_o->body[] = CardKit::nextInCollection((object) array('model_id'=>$_model->id, 'details_route'=>'cards/sequode/details'));
        
        if(\Sequode\Application\Modules\Account\Authority::isSystemOwner()){
            $_o->body[] = CardKitHTML::modelId($_model);
        }
        return $_o;
    }
    public static function internalForms( $_model = null){
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = ($_model == null ) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'), array($_model));
        
        $_o = (object) null;
        $_o->size = 'large';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        
        $_o->head = $_model->name;
        
        $sequence = json_decode($_model->sequence);
        foreach($sequence as $loop_sequence_key => $loop_model_id){
            $_o->body[] = ModuleCard::render($module::$registry_key,'internalPositionForms',array($loop_sequence_key));
        }
        if(\Sequode\Application\Modules\Account\Authority::isSystemOwner()){
            $_o->body[] = CardKitHTML::modelId($_model);
        }
        return $_o;
    }
    public static function internalPositionForms($position, $_model = null){
        $position = intval($position);
        $module = static::$module;
        $modeler = $module::model()->modeler;
        $_model = ($_model == null ) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'), array($_model));
        
        $_o = (object) null;
        $_o->size = 'medium';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->menu = (object) null;
        
        
        $input_object_map = json_decode($_model->input_object_map);
        $default_input_object_map = json_decode($_model->default_input_object_map);
        $property_object_map = json_decode($_model->property_object_map);
        $default_property_object_map = json_decode($_model->default_property_object_map);
        
        $sequence = json_decode($_model->sequence);
        if(!isset($sequence[$position])){ return; }
        $sequence_model_id = $sequence[$position];
        $sequence_model = new \Sequode\Application\Modules\Sequode\Modeler::$model;
        $sequence_model->exists($sequence_model_id,'id');
        
        $_o->head = $sequence_model->name;
        $_o->menu->items = self::modelOperationsMenuItems('', $sequence_model);
        $_o->body = array();
        $_o->body[] = '';
        $sequence_model->input_object_detail = json_decode($sequence_model->input_object_detail);
        $sequence_model->property_object_detail = json_decode($sequence_model->property_object_detail);
        
        $types = array('input', 'property');
        $type_labels = array('input' => 'Inputs', 'property' => 'Properties');
        $detail_objects = array('input' => 'input_object_detail', 'property' => 'property_object_detail');
        $maps = array('input' => $input_object_map, 'property' => $property_object_map);
        $default_maps = array('input' => $default_input_object_map, 'property' => $default_property_object_map);
        
        $possible_components = array();
        
        foreach($types as $type){
            $map = $maps[$type];
            $default_map = $default_maps[$type];
            $detail_object_member = $detail_objects[$type];
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
                $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/sequode/component', array(\Sequode\Component\Form\Form::jsQuotedValue($component->type), $_model->id, $component->map_key)), $text, 'settings');
            }elseif($component->required == false && $component->value_set == false){ 
                $text = $component->member;
                $_o->body[] = DOMElementKitJS::loadComponentHere(DOMElementKitJS::xhrCallObject('forms/sequode/component', array(\Sequode\Component\Form\Form::jsQuotedValue($component->type), $_model->id, $component->map_key)), $text, 'settings');
            }else{
                $components_array = ModuleForm::render($module::$registry_key,'component',array($component->type, $component->map_key, $_model));
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
        $_model = ($_model == null ) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'), array($_model));
        
        $_o = (object) null;
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        
        $_o->head = 'Sequode Chart &gt; Edit &gt; '.$_model->name;
        
        $items = array();
        $items[] = array(
            'css_classes'=>'automagic-card-menu-item-label noSelect',
            'contents'=>'Select Palette'
        );
        $dom_id = FormInputComponent::uniqueHash('','');
        $items[] = array(
            'css_classes'=>'automagic-card-menu-item noSelect',
            'id'=>$dom_id,
            'js_action'=> 'new XHRCall({route:\'forms/sequode/selectPalette\',inputs:['.\Sequode\Component\Form\Form::jsQuotedValue($dom_id).']});'                 
        );
        $_o->menu->items = array_merge($items,$_o->menu->items);
        
        $_o->body = array();
        $dom_id = FormInputComponent::uniqueHash('','');
        $html = $js = array();
        $html[] = '<div class="SequencerStageContainer" id="'.$dom_id.'chart"></div>';
        $js[] = 'var sequencer;';
        $js[] = 'sequencer = new Sequencer();';
        $js[] = 'sequencer.stage = shapesKit.stage({ container: \''.$dom_id.'chart\', width: $(window).width(), height: $(window).height() });';
        $js[] = 'registry.setContext({card:\'cards/sequode/chart\',collection:\'sequodes\',node:'.$_model->id.',tearDown:function(){ sequencer = undefined; }});';
        $js[] = 'registry.subscribeToUpdates({type:\'context\', collection:\'sequodes\', key:true, call: sequencer.run});';
        $js[] = 'registry.subscribeToUpdates({type:\'context\', collection:\'palette\', call: sequencer.palette.run});';
        $js[] = 'registry.fetch({collection:\'sequodes\',key:'.$_model->id.'});';
        $_o->body[] = (object) array('html' => implode('', $html), 'js' => implode(' ', $js));
        
        return $_o;
        
    }
    public static function chart( $_model = null){
        
        $module = static::$module;
        $modeler = $module::model()->modeler;
        
        $_model = ($_model == null ) ? forward_static_call_array(array($modeler,'model'),array()) : forward_static_call_array(array($modeler,'model'), array($_model));
        
        $_o = (object) null;
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = self::modelOperationsMenuItems();
        
        $_o->head = 'Sequode Chart &gt; View &gt; '.$_model->name;
        
        $_o->body = array();
        $dom_id = FormInputComponent::uniqueHash('','');
        $html = $js = array();
        $html[] = '<div class="SequencerStageContainer" id="'.$dom_id.'chart"></div>';
        $js[] = 'var sequencer;';
        $js[] = 'sequencer = new Sequencer();';
        $js[] = 'sequencer.default_events = false;';
        $js[] = 'sequencer.stage = shapesKit.stage({ container: \''.$dom_id.'chart\', width: $(window).width(), height: $(window).height() });';
        $js[] = 'registry.setContext({card:\'cards/sequode/chart\',collection:\'sequodes\',node:'.$_model->id.',tearDown:function(){ sequencer = undefined; }});';
        $js[] = 'registry.subscribeToUpdates({type:\'context\', collection:\'sequodes\', key:true, call: sequencer.run});';
        $js[] = 'registry.fetch({collection:\'sequodes\',key:'.$_model->id.'});';
        $_o->body[] = (object) array('html' => implode('', $html), 'js' => implode(' ', $js));
        
        return $_o;
        
    }
    public static function search($_model = null){
        
        $module = static::$module;
        
        $_o = (object) null;
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
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
        $_o->body[] = CardKit::collectionCard((object) array('collection'=>'sequode_search','icon'=>'sequode','card_route'=>'cards/sequode/search','details_route'=>'cards/sequode/details'));
        
        return $_o;
        
    }
    public static function my(){
        
        $_o = (object) null;
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = array();
        
        $_o->head = 'My Sequodes';
        
        $dom_id = FormInputComponent::uniqueHash('','');
        $_o->menu->items[] = array(
            'css_classes'=>'automagic-card-menu-item noSelect',
            'id'=>$dom_id,
            'contents'=>'New Sequode',
            'js_action'=> DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject('operations/sequode/newSequence'))
        );
        
        $_o->body = array();
        $_o->body[] = CardKit::collectionCard((object) array('collection'=>'my_sequodes','icon'=>'sequode','card_route'=>'cards/sequode/my','details_route'=>'cards/sequode/details'));
        
        return $_o;
        
    }
    public static function favorites(){
        
        $_o = (object) null;
        $_o->size = 'fullscreen';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items = array();
        
        $_o->head = 'Sequode Favorites';
        
        $dom_id = FormInputComponent::uniqueHash('','');
        $_o->menu->items[] = array(
            'css_classes'=>'automagic-card-menu-item noSelect',
            'id'=>$dom_id,
            'contents'=>'Empty Favorites',
            'js_action'=> DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject('operations/user/emptySequodeFavorites',[],'function(){registry.fetch({collection:\'sequode_favorites\'});}'))
        );
        
        $_o->body = array();
        $_o->body[] = CardKit::collectionCard((object) array('collection'=>'sequode_favorites','icon'=>'sequode','card_route'=>'cards/sequode/favorites','details_route'=>'cards/sequode/details'));
        
        return $_o;
        
    }
    
    public static function myTile($user_model=null){
        
        $module = static::$module;
        $context = $module::model()->context;
        
        if($user_model == null ){
            
            $user_model = \Sequode\Application\Modules\Account\Modeler::model();
            
        }
        
        $_o = (object) null;
        $_o->head = 'Sequodes';
        $_o->size = 'xsmall';
        $_o->icon_type = 'menu-icon';
        $_o->icon_background = 'sequode-icon-background';
        $_o->menu = (object) null;
        $_o->menu->items =  array();
        $_o->menu->item[] = CardKit::onTapEventsXHRCallMenuItem('New Sequode','operations/'.$context.'/newSequence');
        $_o->body = array();
        $_o->body[] = '';
        $_o->body[] = CardKit::ownedItemsCollectionTile($module::$registry_key, 'Sequodes : ', $user_model);
        
        
        return $_o;
        
    }
}