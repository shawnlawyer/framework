<?php

namespace Sequode\Application\Modules\Session;

use Sequode\Application\Modules\Traits\Modeler\ActiveModelTrait;

class Modeler {

    use ActiveModelTrait;

    public static $model = Model::class;

	public static function exists($value, $by='session_id'){

        return (self::model()->exists($value, $by)) ? true : false ;

    }
    
}