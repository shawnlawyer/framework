<?php

namespace Sequode\Application\Modules\Sequode;

use Sequode\Application\Models\ORM\Sequodes as Model;

class Modeler extends Sequode\Patterns\Modeler {
    public static $model = Model::class;
}