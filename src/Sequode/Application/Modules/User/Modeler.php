<?php

namespace Sequode\Application\Modules\User;

use Sequode\Application\Modules\User\Model;
use Sequode\Application\Modules\Traits\Modeler\ActiveModelTrait;

class Modeler {

    use ActiveModelTrait;

    public static $model = Model::class;

}