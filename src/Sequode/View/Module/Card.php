<?php
namespace Sequode\View\Module;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Card as CardComponent;

class Card {
    
	public static function render($module_registry_key, $card, $parameters = []){
        
        $module = ModuleRegistry::module($module_registry_key);
        $component_object = forward_static_call_array([$module::model()->components->cards, $card],($parameters === null) ? [] : (!is_array($parameters)) ? [$parameters] : $parameters);
        return CardComponent::render($component_object);
        
	}
    
}