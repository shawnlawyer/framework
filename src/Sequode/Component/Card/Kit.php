<?php
namespace Sequode\Component\Card;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Form;

use Sequode\Component\FormInput as FormInputComponent;
use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;

class Kit {
    public static function collectionMenuItems($module_registry_key, $user_model, $headline=''){
        
        $module = ModuleRegistry::module($module_registry_key);
        $operations = $module::model()->operations;
        $context = $module::model()->context;
        
        $dom_id = FormInputComponent::uniqueHash('','');
        $html = $js = [];
        $models = $operations::getOwnedModels($user_model, 'id,name')->all;
        
        $items[] = [];
        foreach($models as $i => $object){
            $items[] = self::onTapEventsXHRCallMenuItem($object->name, $module::xhrCardRoute('details'), [$object->id]);
        }
        return $items;
    }

    public static function ownedItemsCollectionTile($module_registry_key, $user_model, $headline=''){
        
        $module = ModuleRegistry::module($module_registry_key);
        $operations = $module::model()->operations;
        $context = $module::model()->context;
        
        $dom_id = FormInputComponent::uniqueHash('','');
        $html = $js = [];
        $models = $operations::getOwnedModels($user_model, 'id,name')->all;
        
        $html[] = '<div class="automagic-content-area-xsmall-tile-container">';
        $html[] = '<div class="automagic-card-menu-item noSelect" id="'.$dom_id.'">'.$headline . count($models).'</div>';
        $js[] = DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject($module::xhrCardRoute('my'), [$object->id]));
        foreach($models as $i => $object){
            $html[] = '<div class="automagic-card-menu-item noSelect" id="'.$dom_id.$i.'">';
            $html[] = $object->name;
            $html[] = '</div> ';
            $js[] = DOMElementKitJS::onTapEventsXHRCall($dom_id.$i, DOMElementKitJS::xhrCallObject($module::xhrCardRoute('details'), [$object->id]));
        }
        $html[] = '</div>';
        return (object) ['html' => implode('', $html), 'js' => implode(' ', $js)];
    }
    public static function collectionCard($component){
        if(!(
            isset($component->icon) 
            && isset($component->collection)
            && isset($component->details_route)
            && isset($component->card_route)
        )){
            return (object) ['html' => '', 'js' => ''];
        }
        $dom_id = FormInputComponent::uniqueHash('','');
        $html = $js = [];
        
        $html[] = '<div  class="fitBlock alignCenter" id="'.$dom_id.'"></div>';
        $js[] = 'var cards = new CollectionCards();';
        $js[] = 'cards.container = \''.$dom_id.'\';';
        $js[] = 'cards.icon = \''.$component->icon.'\';';
        $js[] = 'cards.details_route = \''.$component->details_route.'\';';
        $js[] = 'cards.collection = \''.$component->collection.'\';';

        $subscription = (object)[
            'type' => 'context',
            'collection' => $component->collection,
            'call' => 'cards.run'
        ];
        $js[] = DOMElementKitJS::registrySubscribeToUpdates($subscription);
        $js[] = DOMElementKitJS::fetchCollection($component->collection);
        return (object) ['html' => implode('', $html), 'js' => implode(' ', $js)];
    }
    public static function nextInCollection($component){
        if(!(
            isset($component->model_id) 
            && isset($component->details_route)
        )){ 
            return (object) ['html' => '', 'js' => ''];
        }
        $html = $js = [];
        $dom_id = FormInputComponent::uniqueHash('','');
        $html[] = '<span class="automagic-card-next noSelect " id="'.$dom_id.'"></span>';
        if(isset($component->model_id) && isset($component->details_route)){
            $js[] = 'var next_id = registry.nextNode(registry.collection(registry.active_collection), \''.$component->model_id.'\');';
            $js[] = 'if(next_id != \''.$_model->id.'\'){';
            $js[] = 'document.getElementById(\''.$dom_id.'\').innerHTML = registry.node(registry.active_collection, next_id).n + \' &gt;\';';
            $js[] = DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject($component->details_route, ['next_id']));
            $js[] = '}';
        }
        return (object) ['html' => implode('', $html), 'js' => implode(' ', $js)];
    }
    public static function deleteInCollection($component){
        if(!(
            isset($component->model_id)
            && isset($component->route)
            
        )){ 
            return (object) ['html' => '', 'js' => ''];
        }
        $dom_id = FormInputComponent::uniqueHash('','');
        $html = $js = [];
        $html[] = '<span class="automagic-card-delete noSelect " id="'.$dom_id.'">x</span>';
        $js[] = DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject($component->route, [$component->model_id]));
        return (object) ['html' => implode('',$html),'js' => implode('',$js)];
    }
    public static function onTapEventsXHRCallMenuItem($contents, $route, $inputs=null, $callback=null){
        $dom_id = FormInputComponent::uniqueHash('','');
        return [
            'css_classes'=>'automagic-card-menu-item noSelect',
            'id'=>$dom_id,
            'contents'=> $contents,
            'js_action'=> DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject($route,$inputs,$callback))
        ];
    }
    public static function resetDialogButton($route){
        $dom_id = FormInputComponent::uniqueHash('','');
        $html = $js = [];
        $html[] = '<span class="btn" id="'.$dom_id.'">Reset</span>';
        $js[] = DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject($route, [Form::jsQuotedValue('{"reset":"1"}')]));
        return (object) ['html' => implode('',$html),'js' => implode('',$js)];
    }
    public static function onTapEventsXHRCallButton($contents, $route, $inputs=null, $callback=null){
        $dom_id = FormInputComponent::uniqueHash('','');
        $html = $js = [];
        $html[] = '<span class="btn" id="'.$dom_id.'">'.$contents.'</span>';
        $js[] = DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject($route,$inputs,$callback));
        return (object) ['html' => implode('',$html),'js' => implode('',$js)];
    }
    public static function setContext($content, $raw_members=[]){

        $js[] = 'registry.setContext({';
        $js[] = 'card:\''.$content->card_route.'\'';
        if($content->collection){
            $js[] = !in_array('collection', $raw_members)
                ? ', collection:\''.$component->collection.'\''
                : ', collection:'.$component->collection;
        }
        if($content->node){
            $js[] = !in_array('node', $raw_members)
                ? ', node:\''.$component->node.'\''
                : ', node:'.$component->node;
        }
        if($content->tearDown){
            $js[] = ',tearDown:'.$component->tearDown;
        }
        $js[] = ');';
        return (object) ['html' => '','js' => $js];
    }
}