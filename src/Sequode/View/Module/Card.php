<?php
namespace Sequode\View\Module;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Card as CardComponent;

class Card {

    const Modifier_No_Context = 1;
    const Modifier_Small_Tile = 2;
    
	public static function render($module_registry_key, $card, $parameters = [], $modifiers = []){
        
        $module = ModuleRegistry::module($module_registry_key);

        $component_object = forward_static_call_array([$module::model()->components->cards, $card],($parameters === null) ? [] : (!is_array($parameters)) ? [$parameters] : $parameters);

        if(in_array(static::Modifier_No_Context, $modifiers)) {

            $component_object->context = null;

        }

        //$component_object->route = (isset($component_object->context) && isset($component_object->context->card)) ? $component_object->context->card : '';

        if(in_array(static::Modifier_Small_Tile, $modifiers) !== false) {


            $component_object->context = null;

            $component_object->size = 'xsmall';

        }

        //if(!empty($component_object->context)){
        //    unset($component_object->route);
        //}

        return CardComponent::render($component_object);
        
	}
    
}