<?php

namespace Sequode;

class ModuleForm {
	public static function render($package, $form, $parameters = null){
		return \Sequode\Component\Form\Form::render(\Sequode\Component\Form\Form::fetchObject(\Sequode\ModuleRegistry::model($package)->form_objects, $form, ($parameters == null) ? array() : (!is_array($parameters)) ? array($parameters) : $parameters));
	}
}