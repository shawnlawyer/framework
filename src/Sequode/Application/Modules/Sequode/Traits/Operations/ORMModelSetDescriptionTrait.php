<?php

namespace Sequode\Application\Modules\Sequode\Traits\Operations;

trait ORMModelSetDescriptionTrait {
    
    public static function updateDescription($description, $_model = null){
        
        $modeler = static::$modeler;
        $kit = static::$kit;
        
        forward_static_call_array([$modeler, 'model'], ($_model == null) ? [] : [$_model]);
            
        $description = htmlentities(strip_tags($description), ENT_NOQUOTES);
        $description = (strlen($description) <= 1000) ? $description : substr($description, 0, 1000);
        $detail = json_decode($modeler::model()->detail);
        $detail->description = $description;
        
        $modeler::model()->detail = json_encode($detail);
        $modeler::model()->save();

        return $modeler::model();
            
    }
    
}