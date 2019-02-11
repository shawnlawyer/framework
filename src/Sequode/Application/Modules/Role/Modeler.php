<?php

namespace Sequode\Application\Modules\Role;

use Sequode\Application\Modules\Role\Model;

use Sequode\Model\Traits\ModelerTrait;

class Modeler {

    use ModelerTrait;

    public static $model = Model::class;

}