<?php

namespace Sequode\View\Module;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Form\Form as FormComponent;

class Form {
    
	public static function render($module_registry_key, $component, $parameters = null){
        
        
        $module = ModuleRegistry::module($module_registry_key);
        $component_source = $module::model()->components->forms;
                
        $component_object = FormComponent::fetchObject($component_source, $component, ($parameters == null) ? [] : (!is_array($parameters)) ? [$parameters] : $parameters);
        
		return FormComponent::render($component_object);
        
	}
    
}