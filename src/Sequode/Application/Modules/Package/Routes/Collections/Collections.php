<?php

namespace Sequode\Application\Modules\Package\Routes\Collections;

use Sequode\Application\Modules\Package\Module;
use Sequode\Application\Modules\Session\Store as SessionStore;
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
		'packages',
		'package_search',
		'package_favorites',
    ];

    const Routes = [
		'packages',
		'package_search',
		'package_favorites',
    ];

	public static $routes_to_methods = [
		'packages' => 'owned',
		'package_search' => 'search',
		'package_favorites' => 'favorites',
    ];

    const Method_To_Collection = [
        'owned' => 'packages',
        'search' => 'package_search',
        'package' => 'package_favorites',
    ];
}