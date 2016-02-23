<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Foundation\Hashes;

trait ORMModelSignupTrait {
    
    public static function signup($email, $password){
                
        $modeler = static::$modeler;
        
        $modeler::model()->create(Hashes::generateHash($email), Hashes::generateHash($password), $email);
        $modeler::model()->updateField('1', 'active');
        $modeler::model()->updateField('1', 'verified');
        $modeler::model()->updateField('[]', 'sequode_favorites');
        $modeler::model()->updateField('100', 'role_id');
        $modeler::model()->updateField('33', 'allowed_sequode_count');
        $modeler::exists($modeler::model()->id, 'id');
        
        return $modeler::model();
    }
    
}