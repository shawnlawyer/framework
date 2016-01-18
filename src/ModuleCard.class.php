<?php
namespace Sequode;

class ModuleCard {
	public static function render($package, $card, $parameters = null){
		return \Sequode\Component\Card\Card::render(\Sequode\Component\Card\Card::fetchObject(\Sequode\ModuleRegistry::model($package)->card_objects, $card, ($parameters == null) ? array() : (!is_array($parameters)) ? array($parameters) : $parameters));
	}
}