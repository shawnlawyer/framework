<?php

namespace Sequode\Application\Modules\Token;

use Sequode\Application\Modules\Token\Model;
use Sequode\Application\Modules\Traits\Modeler\ActiveModelTrait;

class Modeler {

    use ActiveModelTrait;

    public static $model = Model::class;

}