<?php

namespace Sequode\Application\Modules\SequencerConsole\Routes;

use Sequode\Application\Modules\SequencerConsole\Module;

use Sequode\Application\Modules\Console\Traits\Routes\IndexTrait;
use Sequode\Application\Modules\Console\Traits\Routes\CSSTrait;
use Sequode\Application\Modules\Console\Traits\Routes\JavascriptTrait;
use Sequode\Application\Modules\Console\Traits\Routes\VendorJavascriptTrait;
use Sequode\Application\Modules\Console\Traits\Routes\XHRTrait;
use Sequode\Application\Modules\Console\Traits\Routes\CollectionsTrait;

class Routes{

    use IndexTrait,
        CSSTrait,
        JavascriptTrait,
        VendorJavascript,
        XHRTrait,
        CollectionsTrait;

	public static $module = Module::class;
	public static $merge = false;
	public static $routes = array(
	
        'xhr',
		'collections',
		'application.css',
        'application.js',
		'vendor.js',
	
    );
    
	public static $routes_to_methods = array(
    
		'xhr' => 'xhr',
		'collections' => 'collections',
		'application.css' => 'css',
		'application.js' => 'js',
		'vendor.js' => 'vendorJS',
	
    );
    
}