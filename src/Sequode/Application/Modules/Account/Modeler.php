<?php

namespace Sequode\Application\Modules\Account;

use Sequode\Application\Modules\User\Model as Model;
use Sequode\Application\Modules\Traits\Modeler\ActiveModelTrait;

class Modeler {

    use ActiveModelTrait;

    public static $model = Model::class;

}