<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Foundation\Hashes;

trait ORMModelSignupTrait {
    
    public static function signup($email, $password){
                
        $modeler = static::$modeler;
        
        $modeler::model()->create(Hashes::generateHash($email), Hashes::generateHash($password), $email);
        $modeler::exists($modeler::model()->id, 'id');
        $modeler::model()->active = 1;
        $modeler::model()->verified = 1;
        $modeler::model()->sequode_favorites = [];
        $modeler::model()->role_id = 100;
        $modeler::model()->allowed_sequode_count = 33;
        $modeler::model()->save();
        
        return $modeler::model();
        
    }
    
}