<?php

namespace Sequode\Application\Modules\Account;

use Sequode\Application\Modules\User\Model as Model;

use Sequode\Model\Traits\ModelerTrait;

class Modeler {

    use ModelerTrait;

    public static $model = Model::class;

}