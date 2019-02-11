<?php

namespace Sequode\Application\Modules\BlockedIP;

use Sequode\Application\Modules\BlockedIP\Model;

use Sequode\Model\Traits\ModelerTrait;

class Modeler {

    use ModelerTrait;

    public static $model = Model::class;

}