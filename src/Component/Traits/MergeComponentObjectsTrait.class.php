<?php
namespace Sequode\Component\Traits;

class MergeComponentObjectsTrait {
    
	public static function mergeComponents($component_a, $component_b){
        
        foreach($component_b as $member => $value){
            
            $component_a->$member = (!isset($component_a->$member)) ? $value : $component_a->$member . $value;
            
        }
        
        return $component_a;
        
    }
    
    
}