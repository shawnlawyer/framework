<?php
namespace Sequode\View\Module;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Card\Card as CardComponent;

class Card {
    
	public static function render($registry_key, $component, $parameters = null){
        
        $component_source = ModuleRegistry::model($registry_key)->components->cards;
        
		return CardComponent::render($component_source, $component, ($parameters == null) ? array() : (!is_array($parameters)) ? array($parameters) : $parameters));
        
	}
    
}