<?php

namespace Sequode\Application\Modules\Session\Routes\Collections;

use Sequode\Application\Modules\Session\Store as SessionStore;
use Sequode\Application\Modules\Session\Module;
use Sequode\Application\Modules\Traits\Routes\Collections\CollectionsFavoritesTrait;
use Sequode\Application\Modules\Traits\Routes\Collections\CollectionsSearchTrait;

class Collections{

    use CollectionsSearchTrait,
        CollectionsFavoritesTrait;

    const Module = Module::class;

	public static $merge = false;

	public static $routes = [
		'session_search',
		'session_favorites'
    ];

    const Routes = [
		'session_search',
        'session_favorites'
    ];

	public static $routes_to_methods = [
		'session_search' => 'search',
        'session_favorites' => 'favorites'
    ];

    const Method_To_Collection = [
        'search' => 'session_search',
        'favorites' => 'session_favorites',
    ];

}