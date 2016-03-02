<?php

namespace Sequode\View\Module;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Form\Form as FormComponent;

class Form {
    
	public static function render($registry_key, $component, $parameters = null){
        
        $component_source = ModuleRegistry::model($registry_key)->components->forms;
        
		return CardComponent::render($component_source, $component, ($parameters == null) ? array() : (!is_array($parameters)) ? array($parameters) : $parameters));
        
	}
    
}