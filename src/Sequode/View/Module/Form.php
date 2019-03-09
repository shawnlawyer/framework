<?php

namespace Sequode\View\Module;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Form as FormComponent;

class Form {
    
	public static function render($module_registry_key, $form, $parameters = []){
        
        $module = ModuleRegistry::module($module_registry_key);
        $form_object = forward_static_call_array([$module::model()->components->forms, $form],($parameters === null) ? [] : (!is_array($parameters)) ? [$parameters] : $parameters);
		return FormComponent::render($form_object);
        
	}
    
}