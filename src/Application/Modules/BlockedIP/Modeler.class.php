<?php

namespace Sequode\Application\Modules\BlockedIP;

use Sequode\Application\Models\ORM\BlockedIPs as Model;

class Modeler extends \Sequode\Patterns\Modeler {
	public static $model = Model::class;
}