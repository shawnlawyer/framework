<?php

namespace Sequode\Application\Modules\Token\Traits\Operations;

trait ORMModelCreateTrait {
    
    public static function newToken($owner_id = 0){

        $modeler = static::$modeler;

        return $modeler::create([
            'owner_id' => $owner_id,
            'name' => 'New Token',
            'token' => 'TOK' . sha1(microtime().uniqid(rand(), true))
        ]);
    }
    
}