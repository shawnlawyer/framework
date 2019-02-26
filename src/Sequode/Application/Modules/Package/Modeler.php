<?php

namespace Sequode\Application\Modules\Package;

use Sequode\Application\Modules\Package\Model;
use Sequode\Application\Modules\Traits\Modeler\ActiveModelTrait;

class Modeler {

    use ActiveModelTrait;

    public static $model = Model::class;

}