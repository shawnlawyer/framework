<?php

namespace Sequode\Application\Modules\User;

use Sequode\Application\Modules\User\Model;

use Sequode\Model\Traits\ModelerTrait;

class Modeler {

    use ModelerTrait;

    public static $model = Model::class;

}