<?php

namespace Sequode\Application\Modules\Role;

use Sequode\Application\Modules\Role\Model;
use Sequode\Application\Modules\Traits\Modeler\ActiveModelTrait;

class Modeler {

    use ActiveModelTrait;

    public static $model = Model::class;

}