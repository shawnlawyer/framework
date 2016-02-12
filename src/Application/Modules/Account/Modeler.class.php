<?php

namespace Sequode\Application\Modules\Account;

use Sequode\Application\Models\ORM\Users as Model;

class Modeler extends \Sequode\Patterns\Modeler {
	public static $model = Model::class;
}