<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Foundation\Hashes;

trait ORMModelSignupTrait {
    
    public static function signup($email, $password){
                
        $modeler = static::$modeler;

        return $modeler::create([
            'name' => Hashes::generateHash($email),
            'password' => Hashes::generateHash($password),
            'email' => $email,
            'active' => 1,
            'verified' => 1,
            'sequode_favorites' => [],
            'role_id' => 100,
            'allowed_sequode_count' => 33,
            'sign_up_date' => time(),
            'activation_token' => sha1(substr(md5(uniqid(rand(), true)), 0, 25))
        ]);
        
    }
    
}