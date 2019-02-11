<?php

namespace Sequode\Application\Modules\Token;

use Sequode\Application\Modules\Token\Model;

use Sequode\Model\Traits\ModelerTrait;

class Modeler {

    use ModelerTrait;

    public static $model = Model::class;

}