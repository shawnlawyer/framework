<?php

namespace Sequode\Application\Modules\Token\Routes\Collections;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Application\Modules\Token\Module;
use Sequode\Application\Modules\Account\Modeler as AccountModeler;
use Sequode\Application\Modules\Traits\Routes\Collections\CollectionsFavoritesTrait;
use Sequode\Application\Modules\Traits\Routes\Collections\CollectionsOwnedTrait;
use Sequode\Application\Modules\Traits\Routes\Collections\CollectionsSearchTrait;

class Collections{

    use CollectionsOwnedTrait,
        CollectionsSearchTrait,
        CollectionsFavoritesTrait;

    const Module = Module::class;

	public static $merge = false;

	public static $routes = [
		'tokens',
		'token_search',
		'token_favorites'
    ];

    const Routes = [
		'tokens',
		'token_search',
		'token_favorites'
    ];

	public static $routes_to_methods = [
		'tokens' => 'owned',
		'token_search' => 'search',
		'token_favorites' => 'favorites',
    ];

    const Method_To_Collection = [
        'owned' => 'tokens',
        'search' => 'token_search',
        'favorites' => 'token_favorites',
    ];

}