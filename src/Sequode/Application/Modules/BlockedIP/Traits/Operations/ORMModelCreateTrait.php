<?php

namespace Sequode\Application\Modules\BlockedIP\Traits\Operations;

trait ORMModelCreateTrait {
    
    public static function create($_session_model = null){
        
        $modeler = static::$modeler;
        
        $modeler::model()->create([
            "ip_address" => $_session_model->ip_address
        ]);
        
        return $modeler::model();
    }
    
}