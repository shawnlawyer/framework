<?php

namespace Sequode\Application\Modules\Package\Traits\Operations;

use Sequode\Foundation\Hashes;

trait ORMModelCreateTrait {
    
    public static function newPackage($owner_id = 0){

        $modeler = static::$modeler;

        return $modeler::create([
            'name' => substr(Hashes::uniqueHash('package', 'SQDEPAC'), 0, 15),
            'sequode_id' => 0,
            'owner_id' => $owner_id,
            'name' => 'New Package',
            'token' => sha1(substr(md5(uniqid(rand(), true)), 0, 25))
        ]);
        
	}
    
}