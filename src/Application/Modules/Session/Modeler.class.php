<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Foundation\Hashes;
use Sequode\Model\Application\Configuration;
use Sequode\Application\Models\ORM\Sessions as Model;

class Modeler extends \Sequode\Patterns\Modeler {
    
    use StaticStoreTrait;
    
    public static $model = Model::class;
    
	public static function exists($value,$by='session_id'){
        return (self::model()->exists($value, $by)) ? true : false ;
    }
}