<?php

namespace Sequode\Application\Modules\User\Traits\Operations;

use Sequode\Foundation\Hashes;

trait ORMModelCreateTrait {
    
    
    public static function newUser(){

        $modeler = static::$modeler;

        return $modeler::create([
            'name' => substr(Hashes::uniqueHash(),0,15),
            'password' => substr(Hashes::uniqueHash(),0,15),
            'email'=> substr(Hashes::uniqueHash(),0,15),
            'favorites'=> [],
            'role_id'=> 100,
            'allowed_sequode_count'=> 33,
            'active'=> 1,
            'sign_up_date' => time(),
            'activation_token' => sha1(substr(md5(uniqid(rand(), true)), 0, 25))
        ]);
        
    }
    
    public static function newGuest(){

        $modeler = static::$modeler;

        return $modeler::create([
            'name' => substr(Hashes::uniqueHash(),0,15),
            'password' => substr(Hashes::uniqueHash(),0,15),
            'email'=> substr(Hashes::uniqueHash(),0,15),
            'favorites'=> [],
            'role_id'=> 101,
            'allowed_sequode_count'=> 5,
            'active'=> 1,
            'sign_up_date' => time(),
            'activation_token' => sha1(substr(md5(uniqid(rand(), true)), 0, 25))
        ]);
        
    }
    
}