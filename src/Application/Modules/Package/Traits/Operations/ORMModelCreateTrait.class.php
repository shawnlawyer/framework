<?php

namespace Sequode\Application\Modules\Package\Traits\Operations;

use Sequode\Foundation\Hashes;

trait ORMModelCreateTrait {
    
    public static function newPackage($owner = 0){
        
        $modeler = static::$modeler;
        
        $modeler::model()->create();
        $modeler::exists($modeler::model()->id, 'id');
        $modeler::model()->updateField(substr(Hashes::uniqueHash('package', 'SQDEPAC'), 0, 15), 'name');
        $modeler::model()->updateField($owner, 'owner_id');
        
        return $modeler::model();
        
	}
    
}