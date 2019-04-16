<?php

namespace Sequode\Application\Modules\Register\Routes\XHR;

use Sequode\Application\Modules\Register\Module;
use Sequode\Application\Modules\Traits\Routes\XHR\CardsCardRouteTrait as XHRCardsCardRouteTrait;

class Cards {

    use XHRCardsCardRouteTrait;

    const Module = Module::class;

	public static $routes = [
		'signup'
	];

    const Routes = [
		'signup'
	];

}