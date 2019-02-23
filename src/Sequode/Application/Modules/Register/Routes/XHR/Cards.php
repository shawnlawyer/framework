<?php

namespace Sequode\Application\Modules\Register\Routes\XHR;

use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

use Sequode\Application\Modules\Register\Module;

use Sequode\Component\Card\Traits\CardsTrait;

class Cards {

    use CardsTrait;
    public static $module = Module::class;
	public static $routes = [
		'signup'
	];
}