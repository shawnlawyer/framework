<?php

namespace Sequode\Application\Modules\Console\Traits\Routes;

use Sequode\Component\DOMElement\Kit\HTML as DOMElementKitHTML;

trait IndexTrait {
	
    public static function index(){
    
        $console_module =  ModuleRegistry::model()['Console'];
        $file = $console_module::model()->assets->html['page'];
        header("Content-type: text/html", true);
        echo file_get_contents($file, true);
	
    }
    
}