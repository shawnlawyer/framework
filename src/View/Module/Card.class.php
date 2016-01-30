<?php
namespace Sequode\View\Module;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\Component\Card\Card as CardComponent;

class Card {
	public static function render($package, $card, $parameters = null){
		return CardComponent::render(CardComponent::fetchObject(ModuleRegistry::model($package)->card_objects, $card, ($parameters == null) ? array() : (!is_array($parameters)) ? array($parameters) : $parameters));
	}
}