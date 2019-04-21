<?php
namespace Sequode\View\Module;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Card as CardComponent;

class Card {

    const Modifier_No_Context = 1;
    const Modifier_No_Head = 2;
    const Modifier_Menu_Position_Right = 8;
    const Modifier_Small_Tile = 9;
    
	public static function render($module_registry_key, $card, $parameters = [], $modifiers = []){
        
        $module = ModuleRegistry::module($module_registry_key);

        $component_object = forward_static_call_array([$module::model()->components->cards, $card],($parameters === null) ? [] : (!is_array($parameters)) ? [$parameters] : $parameters);

        if(in_array(static::Modifier_No_Head, $modifiers)) {

            $component_object->head = null;

        }

        if(in_array(static::Modifier_Menu_Position_Right, $modifiers)) {

            $component_object->menu->position = 'right';

        }

        if(in_array(static::Modifier_No_Context, $modifiers)) {

            $component_object->context = null;

        }

        if(in_array(static::Modifier_Small_Tile, $modifiers) !== false) {


            $component_object->context = null;

            $component_object->size = 'xsmall';

        }

        return CardComponent::render($component_object);
        
	}
    
}