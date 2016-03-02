<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Application\Modules\Session\Model;

class Modeler extends \Sequode\Foundation\Modeler {
    
    public static $model = Model::class;
    
	public static function exists($value,$by='session_id'){
        return (self::model()->exists($value, $by)) ? true : false ;
    }
    
}