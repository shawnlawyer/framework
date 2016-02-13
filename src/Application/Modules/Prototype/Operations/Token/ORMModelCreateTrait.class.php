<?php

namespace Sequode\Application\Modules\Prototype\Operations\Token;

trait ORMModelCreateTrait {
    
    public static function newToken($owner_id){
        
        $modeler = static::$modeler;
        
        $modeler::model()->create();
        $modeler::exists($modeler::model()->id, 'id');
        $modeler::model()->updateField($owner_id, 'owner_id');
        
        return $modeler::model();
    }
    
}