<?php

namespace Sequode\Application\Modules\BlockedIP;

use Sequode\Application\Modules\BlockedIP\Model;
use Sequode\Application\Modules\Traits\Modeler\ActiveModelTrait;

class Modeler {

    use ActiveModelTrait;

    public static $model = Model::class;

}