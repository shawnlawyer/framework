<?php
namespace Sequode\Component\FormInput;

use Sequode\Component\DOMElement\Kit\JS as DOMElementKitJS;
use Sequode\Component\Card\Kit\HTML as CardKitHTML;
use Sequode\Application\Modules\FormInput\Modeler;

class FormInput {
    
	public static function mergeComponents($component_a, $component_b){
        
        foreach($component_b as $member => $value){
            
            $component_a->$member = (!isset($component_a->$member)) ? $value : $component_a->$member . $value;
            
        }
        
        return $component_a;
        
    }
    
    
}