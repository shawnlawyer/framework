<?php

namespace Sequode\View\Module;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Form {
	public static function render($package, $form, $parameters = null){
		return \Sequode\Component\Form\Form::render(\Sequode\Component\Form\Form::fetchObject(ModuleRegistry::model($package)->form_objects, $form, ($parameters == null) ? array() : (!is_array($parameters)) ? array($parameters) : $parameters));
	}
}