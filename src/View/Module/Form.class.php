<?php

namespace Sequode\View\Module;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Form\Form as FormComponent;

class Form {
    
	public static function render($registry_key, $component, $parameters = null){
        
        $component_source = ModuleRegistry::model($registry_key)->components->forms;
        
        $component_object = FormComponent::fetchObject($component_source, $component, ($parameters == null) ? array() : (!is_array($parameters)) ? array($parameters) : $parameters);
        
		return FormComponent::render($component_object);
        
	}
    
}