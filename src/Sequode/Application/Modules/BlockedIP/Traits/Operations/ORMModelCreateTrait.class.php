<?php

namespace Sequode\Application\Modules\BlockedIP\Traits\Operations;

trait ORMModelCreateTrait {
    
    public static function create($_session_model){
        
        $modeler = static::$modeler;
        
        $modeler::model()->create($_session_model->ip_address);
        
        return $modeler::model();
    }
    
}