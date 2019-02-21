<?php

namespace Sequode\Application\Modules\Account\Routes\XHR;

use Sequode\Application\Modules\Session\Store as SessionStore;

use Sequode\Model\Module\Registry as ModuleRegistry;
use Sequode\View\Module\Card as ModuleCard;
use Sequode\Component\Card\Kit\JS as CardKitJS;

use Sequode\Application\Modules\Account\Module;
use Sequode\Component\Card\Traits\CardsTrait;

class Cards {

    use CardsTrait;

    public static $module = Module::class;
	public static $routes = [
		'details',
		'updatePassword',
		'updateEmail'
	];
}