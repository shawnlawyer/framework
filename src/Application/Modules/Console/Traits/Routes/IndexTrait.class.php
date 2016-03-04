<?php

namespace Sequode\Application\Modules\Console\Traits\Routes;

use Sequode\Component\DOMElement\Kit\HTML as DOMElementKitHTML;

trait IndexTrait {
	
    public static function index(){
    
        echo DOMElementKitHTML::page();
		exit;
	
    }
    
}