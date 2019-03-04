<?php

namespace Sequode\Application\Modules\Token\Traits\Operations;

trait ORMModelCreateTrait {
    
    public static function newToken($owner_id = 0){
        
        $modeler = static::$modeler;
        
        $modeler::model()->create();
        $modeler::model()->owner_id = $owner_id;
        $modeler::model()->save();

        return $modeler::model();
    }
    
}