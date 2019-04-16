<?php

namespace Sequode\Application\Modules\Account\Routes\XHR;

use Sequode\Application\Modules\Account\Module;
use Sequode\Application\Modules\Traits\Routes\XHR\CardsCardRouteTrait as XHRCardsCardRouteTrait;

class Cards {

    use XHRCardsCardRouteTrait;

    const Module = Module::class;

	public static $routes = [
		'details',
		'updatePassword',
		'updateEmail'
	];

    const Routes = [
		'details',
		'updatePassword',
		'updateEmail'
	];

}