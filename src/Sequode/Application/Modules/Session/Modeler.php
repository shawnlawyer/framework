<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Application\Modules\Session\Model;

use Sequode\Model\Traits\ModelerTrait;

class Modeler {

    use ModelerTrait;

    public static $model = Model::class;

	public static function exists($value,$by='session_id'){

        return (self::model()->exists($value, $by)) ? true : false ;

    }
    
}