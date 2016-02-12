<?php

namespace Sequode\Application\Modules\Prototype\Operations\User;

use Sequode\Foundation\Hashes;

trait ORMModelSignupTrait {
    
    public static function signup($email, $password){
        
        static::$modeler::model()->create(Hashes::generateHash($email), Hashes::generateHash($password), $email);
        static::$modeler::model()->updateField('1', 'active');
        static::$modeler::model()->updateField('1', 'verified');
        static::$modeler::model()->updateField('[]', 'sequode_favorites');
        static::$modeler::model()->updateField('100', 'role_id');
        static::$modeler::model()->updateField('33', 'allowed_sequode_count');
        static::$modeler::exists(static::$modeler::model()->id, 'id');
        
        return static::$modeler::model();
    }
    
}