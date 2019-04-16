<?php
namespace Sequode\Component;

use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit as CardKit;
use Sequode\Component\FormInput as FormInputComponent;

class Card {
    
    public static function cardMenuComponent($menu_object){

        $html = $js = [];
        $position_adjuster = (isset($menu_object->position_adjuster)) ? ' '.$menu_object->position_adjuster : '';

        $html[] = '<div class="automagic-card-menu-container">';
        $html[] = '<div class="automagic-card-menu'.$position_adjuster.'">';
        foreach($menu_object->items as $item){
            $html[] = '<div';
            if(isset($item['css_classes'])){
                $html[] = ' class="'.$item['css_classes'].'"';
            }
            if(isset($item['id'])){
                $html[] = ' id="'.$item['id'].'"';
            }
            $html[] = '>';
            if(isset($item['contents'])){
                $html[] = $item['contents'];
            }
            $html[] = '</div>';
            if(isset($item['js_action'])){
                $js[] = $item['js_action'];
            }
        }
        $html[] = '</div>';
        $html[] = '</div>';
        
        return (object) ['html' => implode('',$html), 'js' => implode(' ',$js)];
        
	}
    public static function cardBodyContentComponent($body_components ,$seperator=''){
        
        if(!is_array($body_components)){
            $body_components = [$body_components];
        }
        
        $html = [];
        $js = [];
        
        if(count($body_components) > 1){
            foreach($body_components as $component){
                if(is_object($component)){
                    if(isset($component->html)){
                        $html[] = $component->html;
                        $html[] = $seperator;
                    }
                    if(isset($component->js)){
                        $js[] = $component->js;
                    }
                }elseif(!is_object($component)){
                    $html[] = $component;
                    $html[] = $seperator;
                }
            }
        }else{
            if(is_object($body_components[0])){
                if(isset($body_components[0]->html)){
                    $html[] = $body_components[0]->html;
                }
                if(isset($body_components[0]->js)){
                    $js[] = $body_components[0]->js;
                }
            }elseif(!is_object($body_components[0])){
                $html[] = $body_components[0];
            }
        }
        
        return (object) ['html' => implode('',$html), 'js' => implode(' ',$js)];
        
	}
    public static function render($card_object){
        
        $html = [];
        $js = [];
        $html[] = '<div class="automagic-card">';
        
        if(!isset($card_object->component_seperator)){
            $card_object->component_seperator = '<div class="automagic-content-row-divider"></div>';
        }

        if($card_object->context){
            $js[] = DOMElementKitJS::registrySetContext($card_object->context);
        }

        if(isset($card_object->head) || isset($card_object->menu)){
            $html[] = '<div class="automagic-card-head';
            if(isset($card_object->size) && $card_object->size == 'fullscreen'){
                $html[] = ' clear-border fullscreen';
            }
            $html[] = '">';
            $html[] = '<div class="';
            $html[] = (isset($card_object->menu)) ? 'menu-icon' : 'card-icon';
            $html[] = (isset($card_object->icon_background)) ? ' '.$card_object->icon_background : '';
            $html[] = '"';
            $html[] = '>';

            if(!empty($card_object->route)){

                $dom_id = FormInputComponent::uniqueHash('','SQDE');
                $html[] = '<div id="' . $dom_id . '" style="z-index:3; position:absolute; right:0; top:35%;">></div>';
                $js[] = DOMElementKitJS::onTapEventsXHRCall($dom_id, DOMElementKitJS::xhrCallObject($card_object->route));

            }

            if(isset($card_object->menu)){
                $menu_component = self::cardMenuComponent($card_object->menu);
                $html[] = $menu_component->html;
                    
                if(isset($menu_component->js)){
                    $js[] = $menu_component->js;
                }
            }
            
            $html[] = '</div>';
            if(isset($card_object->head)){


                $html[] = '<div class="card-title noSelect ">';

                if(is_object($card_object->head)){

                    $html[] = $card_object->head->html;
                    $js[] = $card_object->head->js;

                }else{

                    $html[] = strip_tags($card_object->head);

                }

                $html[] = '</div>';

            }
            $html[] = '</div>';
        }
        
        if(isset($card_object->body)){
            $body_component = self::cardBodyContentComponent($card_object->body, $card_object->component_seperator);
            if(is_object($body_component)){
                if(isset($body_component->html)){
                    $html[] = '<div class="automagic-card-body">';
                    $html[] = '<div class="automagic-content-area-';
                    $html[] = (isset($card_object->size)) ? $card_object->size : 'large' ;
                    $html[] = '">';
                    $html[] = $body_component->html;
                    $html[] = '</div>';
                    $html[] = '</div>';
                }
                if(isset($body_component->js)){
                    $js[] = $body_component->js;
                }
            }
        }

        $html[] = '</div>';
        
        return (object) ['html' => implode('',$html), 'js' => implode('',$js)];
        
    }
}