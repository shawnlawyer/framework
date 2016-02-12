<?php

namespace Sequode\Application\Modules\Prototype\Operations\Token;

trait ORMModelCreateTrait {
    
    public static function newToken($owner_id){
        
        static::$modeler::model()->create();
        static::$modeler::exists($modeler::model()->id, 'id');
        static::$modeler::model()->updateField($owner_id, 'owner_id');
        
        return static::$modeler::model();
    }
    
}