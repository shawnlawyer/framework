<?php

namespace Sequode\Application\Modules\FormInput;

use Sequode\Application\Modules\FormInput\Model as Model;

use Sequode\Model\Traits\ModelerTrait;

class Modeler {

    use ModelerTrait;

	public static $model = Model::class;

}