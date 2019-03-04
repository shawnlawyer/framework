<?php

namespace Sequode\Application\Modules\Package\Traits\Operations;

use Sequode\Foundation\Hashes;

trait ORMModelCreateTrait {
    
    public static function newPackage($owner_id = 0){
        
        $modeler = static::$modeler;
        
        $modeler::model()->create();
        $modeler::model()->name = substr(Hashes::uniqueHash('package', 'SQDEPAC'), 0, 15);
        $modeler::model()->owner_id = $owner_id;
        $modeler::model()->save();
        
        return $modeler::model();
        
	}
    
}