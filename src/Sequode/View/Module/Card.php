<?php
namespace Sequode\View\Module;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Card\Card as CardComponent;

class Card {
    
	public static function render($module_registry_key, $component, $parameters = null){
        
        $module = ModuleRegistry::module($module_registry_key);
        $component_source = $module::model()->components->cards;
        
        $component_object = CardComponent::fetchObject($component_source, $component, ($parameters == null) ? [] : (!is_array($parameters)) ? [$parameters] : $parameters);
        
		return CardComponent::render($component_object);
        
	}
    
}