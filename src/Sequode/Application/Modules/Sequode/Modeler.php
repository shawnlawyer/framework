<?php

namespace Sequode\Application\Modules\Sequode;

use Sequode\Application\Modules\Sequode\Model;

use Sequode\Model\Traits\ModelerTrait;

class Modeler {

    use ModelerTrait;

    public static $model = Model::class;

}