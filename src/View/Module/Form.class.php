<?php

namespace Sequode\View\Module;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Form\Form as FormComponent;

class Form {
	public static function render($package, $form, $parameters = null){
		return FormComponent::render(FormComponent::fetchObject(ModuleRegistry::model($package)->form_objects, $form, ($parameters == null) ? array() : (!is_array($parameters)) ? array($parameters) : $parameters));
	}
}