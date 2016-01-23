<?php
namespace Sequode\View\Module;

use Sequode\Model\Module\Registry as ModuleRegistry;

class Card {
	public static function render($package, $card, $parameters = null){
		return \Sequode\Component\Card\Card::render(\Sequode\Component\Card\Card::fetchObject(ModuleRegistry::model($package)->card_objects, $card, ($parameters == null) ? array() : (!is_array($parameters)) ? array($parameters) : $parameters));
	}
}