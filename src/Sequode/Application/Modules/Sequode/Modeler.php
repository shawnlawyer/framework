<?php

namespace Sequode\Application\Modules\Sequode;

use Sequode\Application\Modules\Sequode\Model;
use Sequode\Application\Modules\Traits\Modeler\ActiveModelTrait;

class Modeler {

    use ActiveModelTrait;

    public static $model = Model::class;

}