<?php

namespace Sequode\Application\Modules\FormInput;

use Sequode\Application\Modules\FormInput\Model as Model;
use Sequode\Application\Modules\Traits\Modeler\ActiveModelTrait;

class Modeler {

    use ActiveModelTrait;

	public static $model = Model::class;

}