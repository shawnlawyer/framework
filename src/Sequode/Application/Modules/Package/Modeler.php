<?php

namespace Sequode\Application\Modules\Package;

use Sequode\Application\Modules\Package\Model;

use Sequode\Model\Traits\ModelerTrait;

class Modeler {

    use ModelerTrait;

    public static $model = Model::class;

}